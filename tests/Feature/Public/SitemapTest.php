<?php

use App\Enums\BlogPostStatus;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

test('sitemap is served as XML with static and catalog URLs', function () {
    $response = get('/sitemap.xml');

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');

    $body = $response->getContent();

    expect($body)
        ->toContain('<?xml version="1.0" encoding="UTF-8"?>')
        ->toContain('<urlset')
        ->toContain('/produtos/agenda-clinic')
        ->toContain('/solucoes/automacao-whatsapp')
        ->toContain('/sobre')
        ->toContain('/cases')
        ->toContain('/contato')
        ->toContain('hreflang="pt-BR"')
        ->toContain('hreflang="es"')
        ->toContain('hreflang="en"');
});

test('sitemap includes published blog posts and excludes drafts', function () {
    $author = User::factory()->create();

    BlogPost::factory()->create([
        'author_id' => $author->id,
        'slug' => 'post-publicado-sitemap',
        'status' => BlogPostStatus::Published->value,
        'published_at' => now()->subDay(),
    ]);

    BlogPost::factory()->create([
        'author_id' => $author->id,
        'slug' => 'post-rascunho-sitemap',
        'status' => BlogPostStatus::Draft->value,
        'published_at' => null,
    ]);

    $body = get('/sitemap.xml')->assertOk()->getContent();

    expect($body)
        ->toContain('/blog/post-publicado-sitemap')
        ->not->toContain('/blog/post-rascunho-sitemap');
});

test('robots.txt file allows crawling and references sitemap', function () {
    $body = file_get_contents(public_path('robots.txt'));

    expect($body)
        ->toContain('User-agent: *')
        ->toContain('Sitemap:')
        ->toContain('/sitemap.xml')
        ->toContain('Disallow: /dashboard');
});
