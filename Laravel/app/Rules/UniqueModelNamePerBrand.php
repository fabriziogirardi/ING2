<?php

namespace App\Rules;

use App\Models\ProductBrand;
use Closure;
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
        if (ProductBrand::where('product_brands.id', $this->data['product_brand_id'])->whereRelation('models', 'name', $value)->exists()) {
            $fail(('manager/model.validation.name.unique_per_brand'));
        }
    }
}
