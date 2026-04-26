<?php

use App\Enums\BlogPostStatus;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('published scope only returns posts published in the past', function () {
    $published = BlogPost::factory()->published()->create();

    BlogPost::factory()->create([
        'status' => BlogPostStatus::Draft,
        'published_at' => null,
    ]);

    BlogPost::factory()->create([
        'status' => BlogPostStatus::Published,
        'published_at' => now()->addHour(),
    ]);

    $publishedIds = BlogPost::query()->published()->pluck('id');

    expect($publishedIds)->toHaveCount(1)
        ->and($publishedIds->first())->toBe($published->id);
});

test('blog posts can be associated with multiple tags', function () {
    $post = BlogPost::factory()->create();
    $tags = BlogTag::factory()->count(3)->create();

    $post->tags()->attach($tags->pluck('id'));
    $post->refresh();

    expect($post->tags)->toHaveCount(3);
});
