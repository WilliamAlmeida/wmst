<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GenerateBlogPostDraftRequest extends FormRequest
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
            'locale' => ['required', Rule::in(['pt_BR', 'es', 'en'])],
            'title_hint' => ['required', 'string', 'max:180'],
            'objective' => ['nullable', 'string', 'max:1200'],
            'target_audience' => ['nullable', 'string', 'max:500'],
            'blog_category_id' => ['nullable', 'integer', 'exists:blog_categories,id'],
            'save_as_draft' => ['sometimes', 'boolean'],
        ];
    }
}
