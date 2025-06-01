<?php

namespace App\Rules;

use App\Models\Customer;
use App\Models\Person;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

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
        if (Customer::whereRelation('person',
            //Es necesario una funcion $query para poder usar whereRelation
            //Y mantener la relacion entre Customer y Person
            function ($query) use ($value) {
                $query->where('government_id_number', $value)
                      ->where('government_id_type_id', $this->data['government_id_type_id']);
            }
        )->exists()) {
            $fail(__('customer/auth.government_id_unique'));
        }
    }
}
