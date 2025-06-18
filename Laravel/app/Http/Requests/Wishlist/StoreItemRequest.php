<?php

namespace App\Http\Requests\Wishlist;

use App\Models\WishlistSublist;
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
            'product_id' => [
                'required',
                // Hay que verificar que no haya maquinas duplicadas en la misma sublista
            ],
            'wishlist_sublist_id' => ['required'],
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date'],
        ];
    }
}
