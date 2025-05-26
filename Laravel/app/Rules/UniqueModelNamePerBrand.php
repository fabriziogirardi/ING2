<?php

namespace App\Rules;

use Closure;
use App\Models\ProductModel;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueModelNamePerBrand implements DataAwareRule, ValidationRule
{
    protected array $data = [];

    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $brandId = $this->data['product_brand_id'];

        $exists = ProductModel::where('name', $value)
            ->where('product_brand_id', $brandId)
            ->exists();

        if ($exists) {
            $fail(__('manager/model.validation.name.unique_per_brand'));
        }
    }
}
