<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Helpers\Upload;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;

class CustomerController extends Controller
{
    /**
     * Retorna lista de clientes.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Customer::with('addresses')->get();
    }

    /**
     * Cria cliente.
     *
     * @param StoreCustomerRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreCustomerRequest $request)
    {
        $this->authorize('create', Customer::class);

        $datas = $request->validated();
        if (! empty($request->file('avatar'))) {
            $datas['avatar'] = Upload::img($request->file('avatar'), 'customer/avatar/');
        }

        $customer = Customer::create($datas);
        if ($customer) {
            return response()->json([
                'customer' => $customer,
                'message' => __('common.created_success'),
            ], 201);
        }

        return response()->json(['message' => __('common.created_fail')], 422);
    }

    /**
     * Exibe dados do cliente.
     *
     * @param Customer $customer
     * @return Customer
     */
    public function show(Customer $customer)
    {
        $customer->load('addresses');

        return $customer;
    }

    /**
     * Atualiza dados de cliente.
     *
     * @param UpdateCustomerRequest $request
     * @param Customer $customer
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $this->authorize('update', $customer);

        $datas = $request->validated();
        if (! empty($request->file('avatar'))) {
            $datas['avatar'] = Upload::img($request->file('avatar'), 'customer/avatar/');
        }

        if ($customer->update($datas)) {
            return response()->json([
                'updates' => $datas,
                'message' => __('common.updated_success'),
            ], 200);
        }

        return response()->json(['message' => __('common.updated_fail')], 422);
    }

    /**
     * Remove cliente.
     *
     * @param Customer $customer
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Customer $customer)
    {
        $this->authorize('delete', $customer);

        if ($customer->delete()) {
            return response()->json(['message' => __('common.deleted_success')], 200);
        }

        return response()->json(['message' => __('common.deleted_fail')], 422);
    }
}
