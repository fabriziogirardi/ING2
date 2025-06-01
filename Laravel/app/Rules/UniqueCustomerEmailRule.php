<?php

namespace App\Rules;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Person;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class UniqueCustomerEmailRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (Customer::whereRelation('person', 'email', $value)->exists()){
            $fail(__('customer/auth.email_unique'));
        }
    }
}
