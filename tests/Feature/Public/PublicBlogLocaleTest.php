<?php

use App\Enums\BlogPostStatus;
use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

test('blog index filters posts by locale', function () {
    BlogPost::factory()->published()->create([
        'locale' => 'pt_BR',
        'slug' => 'post-pt',
        'title' => 'Post PT',
    ]);

    BlogPost::factory()->published()->create([
        'locale' => 'es',
        'slug' => 'post-es',
        'title' => 'Post ES',
    ]);

    BlogPost::factory()->create([
        'locale' => 'pt_BR',
        'title' => 'Rascunho PT',
        'slug' => 'rascunho-pt',
        'status' => BlogPostStatus::Draft,
        'published_at' => null,
    ]);

    get('/blog')
        ->assertOk()
        ->assertSee('Post PT')
        ->assertDontSee('Post ES')
        ->assertDontSee('Rascunho PT');

    get('/es/blog')
        ->assertOk()
        ->assertSee('Post ES')
        ->assertDontSee('Post PT');
});

test('blog detail works in localized routes', function () {
    BlogPost::factory()->published()->create([
        'locale' => 'en',
        'slug' => 'launch-notes',
        'title' => 'Launch Notes',
        'content' => 'English content for product launch.',
    ]);

    get('/en/blog/launch-notes')
        ->assertOk()
        ->assertSee('Launch Notes')
        ->assertSee('English content for product launch.');
});
