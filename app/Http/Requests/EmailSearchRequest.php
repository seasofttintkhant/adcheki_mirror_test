<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailSearchRequest extends FormRequest
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
            'registration_start_date' => 'required_with:registration_end_date|nullable|date|before_or_equal:registration_end_date',
            'registration_end_date' => 'required_with:registration_start_date|nullable|date|after_or_equal:registration_start_date',
            'update_start_date' => 'required_with:update_end_date|nullable|date|before_or_equal:update_end_date',
            'update_end_date' => 'required_with:update_start_date|nullable|date|after_or_equal:update_start_date',
            'email' => 'nullable|email',
            'is_valid_start' => 'required_with:is_valid_end|nullable|numeric|between:0,1',
            'is_valid_end' => 'required_with:is_valid_start|nullable|numeric|between:0,1',
            'status_start' => 'required_with:status_end|nullable|numeric|between:0,2',
            'status_end' => 'required_with:status_start|nullable|numeric|between:0,2'
        ];
    }
}
