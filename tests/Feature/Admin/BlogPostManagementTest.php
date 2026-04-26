<?php

use App\Enums\BlogPostStatus;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\patchJson;
use function Pest\Laravel\post;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

test('guests cannot manage blog posts', function () {
    $response = post(route('admin.blog-posts.store'), []);

    $response->assertRedirect(route('login'));
});

test('authenticated users can create, update and delete blog posts', function () {
    /** @var User $user */
    $user = User::factory()->create();
    $category = BlogCategory::factory()->create(['locale' => 'pt_BR']);
    $tags = BlogTag::factory()->count(2)->create(['locale' => 'pt_BR']);

    actingAs($user);

    $createResponse = postJson(route('admin.blog-posts.store'), [
        'blog_category_id' => $category->id,
        'locale' => 'pt_BR',
        'title' => 'Primeiro Post de Teste',
        'content' => 'Conteudo do post para validar o fluxo administrativo.',
        'status' => BlogPostStatus::Draft->value,
        'tag_ids' => $tags->pluck('id')->all(),
    ]);

    $createResponse->assertCreated();

    $post = BlogPost::query()->first();

    expect($post)->not->toBeNull()
        ->and($post->author_id)->toBe($user->id)
        ->and($post->tags()->count())->toBe(2);

    $updateResponse = patchJson(route('admin.blog-posts.update', $post), [
        'status' => BlogPostStatus::Published->value,
    ]);

    $updateResponse->assertOk();

    expect($post->fresh()->status)->toBe(BlogPostStatus::Published)
        ->and($post->fresh()->published_at)->not->toBeNull();

    $deleteResponse = deleteJson(route('admin.blog-posts.destroy', $post));

    $deleteResponse->assertNoContent();

    assertDatabaseMissing('blog_posts', ['id' => $post->id]);
});
