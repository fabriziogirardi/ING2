<?php

namespace App\Http\Requests\Wishlist;

use Illuminate\Foundation\Http\FormRequest;

class StoreSublistRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                // Falta verificar que el nombre no esté duplicado para el mismo cliente
            ],
        ];
    }
}
