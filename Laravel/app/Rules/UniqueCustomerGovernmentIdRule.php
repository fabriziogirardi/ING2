<?php

namespace App\Rules;

use App\Models\Customer;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueCustomerGovernmentIdRule implements DataAwareRule, ValidationRule
{
    protected array $data = [];

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (Customer::query()
            ->findByGovernmentId($value, $this->data['government_id_type_id'])
            ->exists()) {
            $fail(__('customer/auth.government_id_unique'));
        }
    }
}
