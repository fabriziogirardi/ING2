<?php

namespace App\Rules;

use Closure;
use App\Models\ProductModel;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueModelNamePerBrand implements ValidationRule
{
    protected $brandId;

    public function __construct($brandId)
    {
        $this->brandId = $brandId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = ProductModel::where('name', $value)
            ->where('product_brand_id', $this->brandId)
            ->exists();

        if ($exists) {
            $fail(__('manager/model.validation.name.unique_per_brand'));
        }
    }
}
