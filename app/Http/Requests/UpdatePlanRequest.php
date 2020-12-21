<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlanRequest extends FormRequest
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
            'description' => 'required|string|min:3|max:255',
            'price' => 'regex:/^\d*(\.\d{1,2})?$/',
            'rate' => 'regex:/^\d*(\.\d{1,2})?$/',
            'commission' => 'regex:/^\d*(\.\d{1,2})?$/',
        ];
    }
}
