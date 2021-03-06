<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyReviewRequest extends FormRequest
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
            'review' => 'required|string|min:3|max:255',
            'rate' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'company_uuid' => 'required|uuid|exists:companies,uuid',
        ];
    }
}
