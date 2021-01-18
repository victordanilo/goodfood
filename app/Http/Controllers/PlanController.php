<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Plan;
use App\PaymentMethod;
use App\PaymentStatus;
use Illuminate\Http\Request;
use App\Services\PaymentService;
use Spatie\Permission\Models\Role;
use App\Http\Requests\PlanBuyRequest;
use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;

class PlanController extends Controller
{
    /**
     *  Retorna lista de planos.
     *
     * @return Plan[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Plan::all();
    }

    /**
     * Cria plano.
     *
     * @param StorePlanRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StorePlanRequest $request)
    {
        $this->authorize('create', Plan::class);

        $plan = Plan::create($request->validated());
        if ($plan) {
            Role::create(['name' => $request->input('slug'), 'guard_name' => 'company']);

            return response()->json([
                'plan' => $plan,
                'message' => __('common.created_success'),
            ], 201);
        }

        return response()->json(['message' => __('common.created_fail')], 422);
    }

    /**
     * Exibe dados do plano.
     *
     * @param Plan $plan
     * @return Plan
     */
    public function show(Plan $plan)
    {
        return $plan;
    }

    /**
     * Atualiza dados do plano.
     *
     * @param UpdatePlanRequest $request
     * @param Plan $plan
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        $this->authorize('update', $plan);

        if ($plan->update($request->validated())) {
            $role = Role::where(['name' => $plan->slug, 'guard_name' => 'company'])->first();
            if ($role) {
                $role->update(['name' => $request->input('slug')]);
            }

            return response()->json(['message' => __('common.updated_success')], 200);
        }

        return response()->json(['message' => __('common.updated_fail')], 422);
    }

    /**
     * Remove plano.
     *
     * @param Plan $plan
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Plan $plan)
    {
        $this->authorize('delete', $plan);

        if ($plan->delete()) {
            $role = Role::where(['name' => $plan->slug, 'guard_name' => 'company'])->first();
            if ($role) {
                $role->delete();
            }

            return response()->json(['message' => __('common.deleted_success')], 200);
        }

        return response()->json(['message' => __('common.deleted_fail')], 422);
    }

    /**
     * Sincroniza as permissões do plano.
     *
     * @param Request $request
     * @param Plan $plan
     * @return \Illuminate\Http\JsonResponse
     */
    public function permissions(Request $request, Plan $plan)
    {
        $request->validate([
            'permissons.*' => 'exists:permissions,id',
        ]);

        $role = Role::where(['name' => $plan->slug, 'guard_name' => 'company'])->first();
        if (! empty($role) && $role->syncPermissions($request->input('permissons'))) {
            return response()->json(['message' => __('common.save_success')], 200);
        }

        return response()->json(['message' => __('common.save_fail')], 422);
    }

    /**
     * Assinatura de plano.
     *
     * @param PlanBuyRequest $request
     * @param Plan $plan
     * @return \Illuminate\Http\JsonResponse
     */
    public function buy(PlanBuyRequest $request, Plan $plan)
    {
        try {
            DB::beginTransaction();

            $company = Auth::user();
            $payment = new PaymentService(
                $plan->description,
                $plan->price,
                $request->input('token'),
                $request->input('paymentMethodId'),
                1,
                $company
            );
            $payment = $payment->directProcessing();

            if ($payment->status != 'approved') {
                throw new \Exception(__('common.payment_method_failed'));
            }

            $plan->payment()->create([
                'amount' => $plan->price,
                'mp_payment_id' => $payment->id,
                'plan_uuid' => $plan->uuid,
                'company_uuid' => $company->uuid,
                'method_id' => PaymentMethod::CARD,
                'status_id' => PaymentStatus::APPROVED,
            ]);

            DB::commit();

            return response()->json([
                'message' => __('common.plan_signed_success'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
