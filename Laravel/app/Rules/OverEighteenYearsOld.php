<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class OverEighteenYearsOld implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $age = \Carbon\Carbon::parse($value)
            ->diffInYears(now());

        if ($age < 18) {
            $fail('Debe ser mayor de 18 años');
        }
    }
}
