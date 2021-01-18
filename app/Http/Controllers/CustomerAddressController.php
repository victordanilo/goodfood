<?php

namespace App\Http\Controllers;

use App\Customer;
use App\CustomerAddress;
use App\Http\Requests\AddressRequest;

class CustomerAddressController extends Controller
{
    /**
     * Retorna lista de endereço do cliente selecionado.
     *
     * @param Customer $customer
     * @return mixed
     */
    public function index(Customer $customer)
    {
        return $customer->addresses;
    }

    /**
     * Cria endereço do cliente selecionado.
     *
     * @param AddressRequest $request
     * @param Customer $customer
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(AddressRequest $request, Customer $customer)
    {
        $this->authorize('createAddress', $customer);

        $customerAddress = $customer->addresses()->create($request->validated());
        if ($customerAddress) {
            return response()->json([
                'address' => $customerAddress,
                'message' => __('common.created_success'),
            ], 201);
        }

        return response()->json(['message' => __('common.created_fail')], 422);
    }

    /**
     * Atualizar endereço do cliente selecionado.
     *
     * @param AddressRequest $request
     * @param Customer $customer
     * @param CustomerAddress $address
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(AddressRequest $request, Customer $customer, CustomerAddress $address)
    {
        $this->authorize('updateAddress', $customer);

        if ($address->update($request->validated())) {
            return response()->json(['message' => __('common.updated_success')], 200);
        }

        return response()->json(['message' => __('common.updated_fail')], 422);
    }

    /**
     * Remove o endereço do cliente selecionado.
     *
     * @param Customer $customer
     * @param CustomerAddress $address
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Customer $customer, CustomerAddress $address)
    {
        $this->authorize('deleteAddress', $customer);

        if ($address->delete()) {
            return response()->json(['message' => __('common.deleted_success')], 200);
        }

        return response()->json(['message' => __('common.deleted_fail')], 422);
    }
}
