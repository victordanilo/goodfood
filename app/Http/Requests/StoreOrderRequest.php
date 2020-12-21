<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token' => 'required|string',
            'paymentMethodId' => 'required|string',
            'products' => 'required',
            'products.*.uuid' => 'required|uuid|exists:products,uuid',
            'products.*.qty' => 'required|integer',
            'delivery_address' => 'required|max:255',
            'obs' => 'nullable|string|max:255',
        ];
    }
}
