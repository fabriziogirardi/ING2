<?php

namespace App\Http\Requests\Manager\Customer;

use App\Rules\AdultCustomerRule;
use App\Rules\UniqueCustomerEmailRule;
use App\Rules\UniqueCustomerGovernmentIdRule;
use App\Rules\UniqueEmployeeRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'email'      => [
                'required',
                'string',
                'email',
                'max:255',
                new UniqueCustomerEmailRule(),
            ],
            'government_id_number' => [
                'required',
                'string',
                'size:8',
                new UniqueCustomerGovernmentIdRule(),
            ],
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'birth_date' => [
                'required',
                new AdultCustomerRule(),
            ],
        ];
    }
}
