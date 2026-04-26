<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBlogTagRequest extends FormRequest
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
        $blogTag = $this->route('blogTag');

        return [
            'locale' => ['sometimes', Rule::in(['pt_BR', 'es', 'en'])],
            'name' => ['sometimes', 'string', 'max:120'],
            'slug' => [
                'sometimes',
                'nullable',
                'string',
                'max:140',
                Rule::unique('blog_tags', 'slug')
                    ->ignore($blogTag?->id)
                    ->where(fn ($query) => $query->where('locale', $this->input('locale', $blogTag?->locale))),
            ],
        ];
    }
}
