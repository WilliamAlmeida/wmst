<?php

namespace App\Http\Requests\Admin;

use App\Enums\AgentShareLinkExpiryType;
use App\Enums\AgentShareLinkUsageType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAgentShareLinkRequest extends FormRequest
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
            'assigned_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'label' => ['nullable', 'string', 'max:120'],
            'expiry_type' => ['required', Rule::enum(AgentShareLinkExpiryType::class)],
            'expires_at' => ['nullable', 'date', 'required_if:expiry_type,'.AgentShareLinkExpiryType::FixedDatetime->value],
            'expires_in_minutes' => ['nullable', 'integer', 'min:1', 'required_if:expiry_type,'.AgentShareLinkExpiryType::RelativeDuration->value],
            'issued_at' => ['nullable', 'date'],
            'usage_type' => ['required', Rule::enum(AgentShareLinkUsageType::class)],
            'max_uses' => ['nullable', 'integer', 'min:1'],
            'meta' => ['nullable', 'array'],
        ];
    }
}
