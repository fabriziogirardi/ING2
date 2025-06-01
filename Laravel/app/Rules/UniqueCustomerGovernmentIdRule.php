<?php

namespace App\Rules;

use App\Models\Customer;
use App\Models\Employee;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class UniqueCustomerGovernmentIdRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (Customer::whereRelation('person', 'government_id_number', $value)->exists()){
            $fail(__('customer/auth.government_id_unique'));
        }
    }
}
