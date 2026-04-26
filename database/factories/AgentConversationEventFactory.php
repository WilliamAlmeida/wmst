<?php

namespace Database\Factories;

use App\Models\AgentConversationEvent;
use App\Models\AgentShareLink;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AgentConversationEvent>
 */
class AgentConversationEventFactory extends Factory
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
            'event_name' => fake()->randomElement(['conversation.started', 'message.sent', 'message.received']),
            'event_at' => now(),
            'external_event_id' => fake()->uuid(),
            'payload' => ['channel' => 'chat'],
        ];
    }
}
