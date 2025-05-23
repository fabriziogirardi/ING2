<?php

namespace App\Http\Requests\Manager\Employee;

use Illuminate\Foundation\Http\FormRequest;

class RegisterEmployeeRequest extends FormRequest
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
            'first_name'            => 'required|string|max:255',
            'last_name'             => 'required|string|max:255',
            'email'                 => 'required|email|unique:people,email',
            'password'              => 'required|string|min:8|confirmed',
            'government_id_type_id' => 'required|int|exists:government_id_types,id',
            'government_id_number'  => 'required|string|max:255|unique:people,government_id',
        ];
    }
}
