<?php

namespace Database\Factories;

use App\Models\AiAgent;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<AiAgent>
 */
class AiAgentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->company().' Assistant';

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numerify('###'),
            'description' => fake()->sentence(16),
            'system_prompt' => fake()->paragraph(),
            'provider' => fake()->randomElement(['openai', 'anthropic', 'gemini']),
            'model' => fake()->randomElement(['gpt-4o-mini', 'claude-3-5-sonnet', 'gemini-2.0-flash']),
            'is_active' => true,
            'metadata' => ['temperature' => 0.2],
        ];
    }
}
