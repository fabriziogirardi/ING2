<?php

namespace App\Http\Requests\Wishlist;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'product_id' => [
                'required',
                // Hay que verificar que no haya maquinas duplicadas en la misma sublista
            ],
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date'],
        ];
    }
}
