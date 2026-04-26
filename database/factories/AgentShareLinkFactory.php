<?php

namespace Database\Factories;

use App\Enums\AgentShareLinkExpiryType;
use App\Enums\AgentShareLinkUsageType;
use App\Models\AgentShareLink;
use App\Models\AiAgent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<AgentShareLink>
 */
class AgentShareLinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ai_agent_id' => AiAgent::factory(),
            'created_by_user_id' => User::factory(),
            'assigned_user_id' => null,
            'token' => Str::random(48),
            'label' => fake()->words(2, true),
            'expiry_type' => AgentShareLinkExpiryType::FixedDatetime,
            'expires_at' => now()->addDays(7),
            'expires_in_minutes' => null,
            'issued_at' => now(),
            'usage_type' => AgentShareLinkUsageType::MultiUse,
            'max_uses' => null,
            'used_count' => 0,
            'first_accessed_at' => null,
            'last_accessed_at' => null,
            'revoked_at' => null,
            'meta' => null,
        ];
    }

    public function singleUse(): static
    {
        return $this->state(fn (array $attributes) => [
            'usage_type' => AgentShareLinkUsageType::SingleUse,
            'max_uses' => 1,
        ]);
    }

    public function durationBased(int $minutes = 10080): static
    {
        return $this->state(fn (array $attributes) => [
            'expiry_type' => AgentShareLinkExpiryType::RelativeDuration,
            'expires_at' => null,
            'expires_in_minutes' => $minutes,
            'issued_at' => now(),
        ]);
    }
}
