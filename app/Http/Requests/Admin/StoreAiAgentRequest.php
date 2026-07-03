<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAiAgentRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:160', Rule::unique('ai_agents', 'slug')],
            'description' => ['nullable', 'string'],
            'system_prompt' => ['nullable', 'string'],
            'provider' => ['nullable', 'string', 'max:50'],
            'model' => ['nullable', 'string', 'max:120'],
            'is_active' => ['sometimes', 'boolean'],
            'metadata' => ['nullable', 'array'],
            'max_interactions' => ['sometimes', 'integer', 'min:1', 'max:500'],
            'max_message_length' => ['sometimes', 'integer', 'min:50', 'max:5000'],
        ];
    }
}
