<?php

namespace App\Http\Requests\Manager\ForumSection;

use Illuminate\Foundation\Http\FormRequest;

class UpdateForumSectionRequest extends FormRequest
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
            'name' => 'required|unique:forum_sections,name|string|max:255|min:1',
        ];
    }
}
