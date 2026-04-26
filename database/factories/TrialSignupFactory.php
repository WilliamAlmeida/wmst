<?php

namespace Database\Factories;

use App\Models\AgentShareLink;
use App\Models\AiAgent;
use App\Models\TrialSignup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TrialSignup>
 */
class TrialSignupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'company' => fake()->company(),
            'phone' => fake()->phoneNumber(),
            'message' => fake()->optional()->sentence(12),
            'preferred_locale' => fake()->randomElement(['pt_BR', 'es', 'en']),
            'product' => fake()->randomElement(['agenda_clinic', 'ibox_delivery', 'conecta', 'custom']),
            'ai_agent_slug' => null,
            'consent_accepted' => true,
            'metadata' => null,
            'ai_agent_id' => AiAgent::factory(),
            'agent_share_link_id' => AgentShareLink::factory(),
        ];
    }
}
