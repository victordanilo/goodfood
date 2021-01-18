<?php

namespace App\Http\Controllers;

use DB;
use Log;
use Auth;
use App\Order;
use App\Company;
use App\OrderStatus;
use App\PaymentMethod;
use App\PaymentStatus;
use App\Services\PaymentService;
use App\Services\CartProcessService;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreOrderRequest;
use App\Services\CalculateDeliveryService;
use App\Http\Requests\PreviewDeliveryPriceRequest;

class OrderController extends Controller
{
    /**
     * Retorna os pedidos do usuário logado.
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        if (empty(Auth::user())) {
            return response()->json(['message' => __('auth.not_authenticated')], 422);
        }

        $user = Auth::user();

        return $user->orders()->with(['company', 'customer', 'status'])->get();
    }

    /**
     * Retornas todos os pedidos.
     *
     * @return Order[]|\Illuminate\Database\Eloquent\Collection
     */
    public function indexAdmin()
    {
        return Order::with(['company', 'customer', 'status'])->get();
    }

    /**
     * Cria um pedido.
     *
     * @param StoreOrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreOrderRequest $request)
    {
        try {
            $this->authorize('create', Order::class);

            $cart = new CartProcessService($request->input('products'));
            $orders = [];
            $customer = Auth::user();
            $payment = new PaymentService(
                '',
                0,
                $request->input('token'),
                $request->input('paymentMethodId'),
                1,
                $customer
            );
            $paymentDescription = [];

            DB::beginTransaction();
            foreach ($cart->productByCompany as $company_uuid => $products) {
                $delivery_price = $this->calculateDelivery(
                    $cart->companies[$company_uuid],
                    $request->input('delivery_address')
                );

                // processa o pedido
                $orders[$company_uuid] = Order::create([
                    'company_uuid' => $company_uuid,
                    'customer_uuid' => $customer->uuid,
                    'amount' => $cart->amount[$company_uuid],
                    'delivery_price' => $delivery_price,
                    'delivery_address' => $request->input('delivery_address'),
                    'status_id' => OrderStatus::PENDING,
                    'obs' => $request->input('obs'),
                ]);

                // processa os produtos do pedido
                foreach ($products as $item) {
                    if ($item->product->removeStock($item->qty) === false) {
                        throw new \Exception(__('common.order_process_fail'));
                    }

                    $paymentDescription[] = "{$item->qty}x {$item->product->description}";
                    $orders[$company_uuid]->items()->create([
                        'product_uuid' => $item->product->uuid,
                        'price' => $item->product->price,
                        'qty' => $item->qty,
                    ]);
                }

                // add sprint de pagamento
                $total = number_format($cart->amount[$company_uuid] + $delivery_price, 2);
                $payment->addDisbursement($total, $cart->companies[$company_uuid]);
            }

            //  processa pagamento
            $paymentDescription = implode(', ', $paymentDescription);
            $payment->setDescription($paymentDescription);
            $payment = $payment->marketPlaceProcessing();

            if ($payment->status != 'approved') {
                throw new \Exception(__('common.payment_method_failed'));
            }

            foreach ($orders as $order) {
                $order->payment()->create([
                    'amount' => number_format(($order->amount + $order->delivery_price), 2),
                    'mp_payment_id' => $payment->id,
                    'order_uuid' => $order->uuid,
                    'customer_uuid' => $customer->uuid,
                    'method_id' => PaymentMethod::CARD,
                    'status_id' => PaymentStatus::APPROVED,
                ]);
            }
            DB::commit();

            return response()->json([
                'orders' => $orders,
                'message' => __('common.order_process_success'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Exibe dados do pedido.
     *
     * @param Order $order
     * @return Order
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load('customer', 'company', 'items.products', 'status');

        return $order;
    }

    /**
     * Remove o pedido.
     *
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);

        if ($order->delete()) {
            return response()->json(['message' => __('common.deleted_success')], 200);
        }

        return response()->json(['message' => __('common.deleted_fail')], 422);
    }

    /**
     * Pré Calcula o preço de entrega.
     *
     * @param PreviewDeliveryPriceRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function previewDeliveryPrice(PreviewDeliveryPriceRequest $request)
    {
        try {
            $total = 0.00;
            $cart = new CartProcessService($request->input('products'));

            foreach ($cart->productByCompany as $company_uuid => $products) {
                $total += $this->calculateDelivery(
                    $cart->companies[$company_uuid],
                    $request->input('delivery_address')
                );
            }

            return response()->json(['delivery_price' => number_format($total, 2)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Calcular preço de entrega.
     *
     * @param Company $company
     * @param string $address
     * @return float
     */
    private function calculateDelivery(Company $company, string $address)
    {
        $addressHash = sha1($address);
        $addressHash = "{$company->uuid}-{$company->delivery_cost}-{$addressHash}";

        return Cache::remember("address:{$addressHash}", now()->addMinute(10), function () use ($company, $address) {
            return (new CalculateDeliveryService($company, $address))->getPrice();
        });
    }
}
