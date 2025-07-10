<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class StoreReturnedReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'government_id_type_id' => 'required|integer|exists:government_id_types,id',
            'government_id_number'  => 'required|min:7',
            'code'                  => 'required|string|size:8',
            'description'           => 'nullable|string|required_if:rating,0,1,2',
            'rating'                => 'required|integer|between:0,5',
        ];
    }
}
