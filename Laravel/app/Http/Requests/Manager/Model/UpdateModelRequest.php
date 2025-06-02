<?php

namespace App\Http\Requests\Manager\Model;

use App\Rules\UniqueModelNamePerBrand;
use Illuminate\Foundation\Http\FormRequest;

class UpdateModelRequest extends FormRequest
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
