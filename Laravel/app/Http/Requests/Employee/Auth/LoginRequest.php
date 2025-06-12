<?php

namespace App\Http\Requests\Employee\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email'     => 'required',
            'password'  => 'required',
            'branch_id' => 'required|exists:branches,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'email'     => __('employee/auth.label_email'),
            'password'  => __('employee/auth.label_password'),
            'branch_id' => __('employee/auth.label_branch'),
        ];
    }
}
