<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
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
            'name' => 'nullable|string|min:3|max:255',
            'trade' => 'nullable|string|min:3|max:255',
            'cpf_cnpj' => 'nullable|string|formato_cpf_cnpj|cpf_cnpj',
            'phone' => 'nullable|string|min:10|max:11|regex:/[0-9]{9}/',
            'owner' => 'nullable|string|min:3|max:255',
            'email' => 'nullable|string|email|unique:users',
            'password' => 'string',
            'delivery_cost' => 'regex:/^\d*(\.\d{1,2})?$/',
            'avatar' => 'nullable|sometimes|mimes:jpeg,jpg,png,gif|max:50000',
            'level' => 'integer',
            'lat' => 'nullable|string',
            'lng' => 'nullable|string',
            'street' => 'nullable|string|min:3|max:255',
            'n' => 'nullable|string',
            'complement' => 'nullable|string|min:3|max:255',
            'district' => 'nullable|string|min:3|max:255',
            'zip_code' => 'nullable|string|min:3|max:255',
            'city' => 'nullable|string|min:3|max:255',
            'uf' => 'nullable|string|size:2',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('password')) {
            $this->merge(['password' => bcrypt($this->password)]);
        }
    }
}
