<?php

namespace App\Http\Requests\Manager\Auth;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email'    => 'required|email|exists:people,email',
            'password' => 'required|string',
        ];
    }

    /**
     * Get the validation error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required'    => 'El email es obligatorio',
            'email.email'       => 'El email debe ser una dirección de correo electrónico válida',
            'email.exists'      => 'El email no existe en la base de datos',
            'password.required' => 'La contraseña es obligatoria',
            'password.string'   => 'La contraseña debe ser una cadena de texto',
        ];
    }
}
