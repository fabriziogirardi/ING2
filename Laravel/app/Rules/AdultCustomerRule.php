<?php

namespace App\Rules;

use App\Models\Customer;
use App\Models\Employee;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Carbon;
use Illuminate\Translation\PotentiallyTranslatedString;

class AdultCustomerRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $birthDate = Carbon::parse($value);
        $adultDate = now()->subYears(18);

        if ($birthDate->greaterThan($adultDate)) {
            $fail(__('customer/auth.birth_date_adult'));
        }
    }
}
