<?php

namespace Database\Factories;

use App\Enums\BlogPostStatus;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<BlogPost>
 */
class BlogPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(6);

        return [
            'author_id' => User::factory(),
            'blog_category_id' => BlogCategory::factory(),
            'locale' => fake()->randomElement(['pt_BR', 'es', 'en']),
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numerify('####'),
            'excerpt' => fake()->paragraph(),
            'content' => fake()->paragraphs(4, true),
            'status' => BlogPostStatus::Draft,
            'featured_image_path' => null,
            'seo_title' => $title,
            'seo_description' => fake()->sentence(12),
            'seo_og_image_path' => null,
            'scheduled_for' => null,
            'published_at' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BlogPostStatus::Published,
            'scheduled_for' => null,
            'published_at' => now()->subHour(),
        ]);
    }

    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BlogPostStatus::Scheduled,
            'scheduled_for' => now()->addDay(),
            'published_at' => null,
        ]);
    }
}
