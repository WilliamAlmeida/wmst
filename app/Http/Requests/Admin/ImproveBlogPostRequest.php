<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ImproveBlogPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'feedback' => ['required', 'string', 'max:3000'],
            'current_title' => ['nullable', 'string', 'max:180'],
            'current_excerpt' => ['nullable', 'string'],
            'current_content' => ['nullable', 'string'],
            'current_seo_title' => ['nullable', 'string', 'max:180'],
            'current_seo_description' => ['nullable', 'string'],
        ];
    }
}
