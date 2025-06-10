<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBranchRequest extends FormRequest
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
        $branchId = $this->route('branch')->id ?? null;

        return [
            'place_id'    => "sometimes|required|string|unique:branches,place_id,$branchId",
            'name'        => 'sometimes|required|string|max:255',
            'address'     => 'sometimes|required|string|max:255',
            'latitude'    => 'sometimes|required|numeric',
            'longitude'   => 'sometimes|required|numeric',
            'description' => 'nullable|string',
        ];
    }
}
