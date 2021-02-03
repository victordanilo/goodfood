<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'lat' => 'nullable|string',
            'lng' => 'nullable|string',
            'street' => 'nullable|string|min:3|max:255',
            'n' => 'nullable|string',
            'complement' => 'nullable|string|min:3|max:255',
            'district' => 'nullable|string|min:3|max:255',
            'zip_code' => 'nullable|string',
            'city' => 'nullable|string|min:3|max:255',
            'uf' => 'nullable|string|size:2',
        ];
    }
}
