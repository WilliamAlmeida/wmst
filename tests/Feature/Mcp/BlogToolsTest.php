<?php

use App\Mcp\Tools\CreateBlogPost;
use App\Mcp\Tools\DeleteBlogPost;
use App\Mcp\Tools\GetBlogPost;
use App\Mcp\Tools\ListBlogPosts;
use App\Mcp\Tools\UpdateBlogPost;
use App\Models\BlogPost;
use App\Models\User;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;

test('create tool publishes a post authored by the first user', function () {
    $admin = User::factory()->create();

    (new CreateBlogPost)->handle(new Request([
        'title' => 'Post via MCP',
        'content' => '# Olá\n\nConteúdo de teste.',
        'status' => 'published',
    ]));

    $post = BlogPost::whereTitle('Post via MCP')->first();

    expect($post)->not->toBeNull();
    expect($post->locale)->toBe('pt_BR');
    expect($post->status->value)->toBe('published');
    expect($post->published_at)->not->toBeNull();
    expect($post->author_id)->toBe($admin->id);
    expect($post->slug)->toBe('post-via-mcp');
});

test('create tool fails without content and creates nothing', function () {
    User::factory()->create();

    (new CreateBlogPost)->handle(new Request(['title' => 'Sem conteúdo']));

    expect(BlogPost::count())->toBe(0);
});

test('create tool generates unique slugs per locale', function () {
    User::factory()->create();

    (new CreateBlogPost)->handle(new Request(['title' => 'Repetido', 'content' => 'a']));
    (new CreateBlogPost)->handle(new Request(['title' => 'Repetido', 'content' => 'b']));

    expect(BlogPost::pluck('slug')->all())->toEqualCanonicalizing(['repetido', 'repetido-1']);
});

test('update tool changes fields and re-slugs on title change', function () {
    $admin = User::factory()->create();
    $post = BlogPost::factory()->create(['author_id' => $admin->id, 'locale' => 'pt_BR', 'status' => 'draft']);

    (new UpdateBlogPost)->handle(new Request([
        'id' => $post->id,
        'title' => 'Título Atualizado',
        'status' => 'published',
    ]));

    $fresh = $post->fresh();
    expect($fresh->title)->toBe('Título Atualizado');
    expect($fresh->slug)->toBe('titulo-atualizado');
    expect($fresh->status->value)->toBe('published');
    expect($fresh->published_at)->not->toBeNull();
});

test('list and get tools return posts', function () {
    $admin = User::factory()->create();
    $post = BlogPost::factory()->create(['author_id' => $admin->id, 'title' => 'Listável']);

    (new ListBlogPosts)->handle(new Request(['limit' => 10]));
    $response = (new GetBlogPost)->handle(new Request(['id' => $post->id]));

    expect($response)->toBeInstanceOf(Response::class);
    expect(BlogPost::whereKey($post->id)->exists())->toBeTrue();
});

test('delete tool removes a post', function () {
    $admin = User::factory()->create();
    $post = BlogPost::factory()->create(['author_id' => $admin->id]);

    (new DeleteBlogPost)->handle(new Request(['id' => $post->id]));

    expect(BlogPost::whereKey($post->id)->exists())->toBeFalse();
});
