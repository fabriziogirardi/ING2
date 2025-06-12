<?php

namespace App\Http\Requests\Customer;

use App\Rules\AdultCustomerRule;
use App\Rules\UniqueCustomerEmailRule;
use App\Rules\UniqueCustomerGovernmentIdRule;
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
            'email' => [
                'required',
                'email',
                'max:255',
                new UniqueCustomerEmailRule,
            ],
            'government_id_number' => [
                'required',
                'string',
                'min:7',
                'max:8',
                new UniqueCustomerGovernmentIdRule,
            ],
            'government_id_type_id' => [
                'required',
                'integer',
                'exists:government_id_types,id',
            ],
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'birth_date' => [
                'required',
                new AdultCustomerRule,
            ],
        ];
    }
}
