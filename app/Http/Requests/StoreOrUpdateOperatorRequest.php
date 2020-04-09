<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateOperatorRequest extends FormRequest
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
        $passwordRules = 'required';
        if (request()->method() === "PUT") {
            $passwordRules = 'nullable';
        }
        return [
            'login_id' => 'required|unique:admins,login_id,' . $this->id,
            'password' => $passwordRules.'|min:8',
            'role' => 'required',
            'permitted_ip' => 'required|ip'
        ];
    }
}
