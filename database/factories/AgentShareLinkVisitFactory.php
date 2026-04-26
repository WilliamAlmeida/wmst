<?php

namespace Database\Factories;

use App\Models\AgentShareLink;
use App\Models\AgentShareLinkVisit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AgentShareLinkVisit>
 */
class AgentShareLinkVisitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'agent_share_link_id' => AgentShareLink::factory(),
            'user_id' => null,
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'referrer' => fake()->optional()->url(),
            'visited_at' => now(),
            'metadata' => null,
        ];
    }
}
