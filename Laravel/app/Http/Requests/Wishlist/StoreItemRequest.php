<?php

namespace App\Http\Requests\Wishlist;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreItemRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'machine_id' => [
                'required',
                'exists:machines,id',
                // Hay que verificar que no haya maquinas duplicadas en la misma sublista
            ],
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date'],
        ];
    }
}
