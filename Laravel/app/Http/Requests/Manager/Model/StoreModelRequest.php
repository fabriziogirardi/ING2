<?php

namespace App\Http\Requests\Manager\Model;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UniqueModelNamePerBrand;

class StoreModelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                new UniqueModelNamePerBrand($this->input('product_brand_id')),
            ],
            'product_brand_id' => 'required|exists:product_brands,id',
        ];
    }
}
