<?php

namespace App\Rules;

use App\Models\Employee;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueEmployeeRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (Employee::whereRelation('person', 'email', $value)->exists()) {
            $fail(__('manager/employee.validation.email.unique'));
        }
    }
}
