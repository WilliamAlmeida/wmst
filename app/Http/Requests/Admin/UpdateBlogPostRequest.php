<?php

namespace App\Http\Requests\Admin;

use App\Enums\BlogPostStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBlogPostRequest extends FormRequest
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
        $blogPost = $this->route('blogPost');

        return [
            'blog_category_id' => ['sometimes', 'nullable', 'integer', 'exists:blog_categories,id'],
            'locale' => ['sometimes', 'string', Rule::in(['pt_BR', 'es', 'en'])],
            'title' => ['sometimes', 'string', 'max:180'],
            'slug' => [
                'sometimes',
                'nullable',
                'string',
                'max:180',
                Rule::unique('blog_posts', 'slug')
                    ->ignore($blogPost?->id)
                    ->where(fn ($query) => $query->where('locale', $this->input('locale', $blogPost?->locale))),
            ],
            'excerpt' => ['sometimes', 'nullable', 'string'],
            'content' => ['sometimes', 'string'],
            'status' => ['sometimes', Rule::enum(BlogPostStatus::class)],
            'featured_image_path' => ['sometimes', 'nullable', 'string', 'max:255'],
            'seo_title' => ['sometimes', 'nullable', 'string', 'max:180'],
            'seo_description' => ['sometimes', 'nullable', 'string'],
            'seo_og_image_path' => ['sometimes', 'nullable', 'string', 'max:255'],
            'scheduled_for' => ['sometimes', 'nullable', 'date', 'required_if:status,'.BlogPostStatus::Scheduled->value],
            'published_at' => ['sometimes', 'nullable', 'date'],
            'tag_ids' => ['sometimes', 'nullable', 'array'],
            'tag_ids.*' => ['integer', 'exists:blog_tags,id'],
        ];
    }
}
