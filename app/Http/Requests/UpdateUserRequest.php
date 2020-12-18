<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
