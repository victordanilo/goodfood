<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
            'cpf_cnpj' => 'string|formato_cpf_cnpj|cpf_cnpj',
            'phone' => 'nullable|string|min:10|max:11|regex:/[0-9]{9}/',
            'email' => 'string|email|unique:users',
            'password' => 'string',
            'avatar' => 'nullable|sometimes|mimes:jpeg,jpg,png,gif|max:50000',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('password')) {
            $this->merge(['password' => bcrypt($this->password)]);
        }
    }
}
