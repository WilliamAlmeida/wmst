<?php

use App\Ai\Services\BlogPostAgentService;
use App\Enums\BlogPostStatus;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Laravel\mock;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

test('authenticated users can access posting agent page', function () {
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user);

    BlogCategory::factory()->create(['locale' => 'pt_BR']);
    BlogPost::factory()->count(2)->create(['locale' => 'pt_BR']);

    $response = get(route('admin.blog-post-agent.index'));

    $response
        ->assertOk()
        ->assertSee('"component":"admin\\/BlogPosts\\/PostingAgent"', false);
});

test('posting agent can generate and save draft for review', function () {
    /** @var User $user */
    $user = User::factory()->create();
    $category = BlogCategory::factory()->create(['locale' => 'pt_BR']);

    actingAs($user);

    mock(BlogPostAgentService::class, function (MockInterface $mock): void {
        $mock->shouldReceive('generateDraft')->once()->andReturn([
            'analysis' => 'Analise de exemplo',
            'action_plan' => 'Plano de exemplo',
            'suggestion' => [
                'title' => 'Post sugerido para avaliacao',
                'slug' => 'post-sugerido-para-avaliacao',
                'excerpt' => 'Resumo sugerido',
                'content' => 'Conteudo sugerido',
                'seo_title' => 'SEO titulo',
                'seo_description' => 'SEO descricao',
            ],
        ]);
    });

    $response = postJson(route('admin.blog-post-agent.generate'), [
        'locale' => 'pt_BR',
        'title_hint' => 'Tema de teste',
        'objective' => 'Validar criacao de draft',
        'target_audience' => 'Gestores de marketing',
        'blog_category_id' => $category->id,
        'save_as_draft' => true,
    ]);

    $response
        ->assertCreated()
        ->assertJsonPath('draft.title', 'Post sugerido para avaliacao');

    assertDatabaseHas('blog_posts', [
        'title' => 'Post sugerido para avaliacao',
        'status' => BlogPostStatus::Draft->value,
        'locale' => 'pt_BR',
    ]);
});

test('posting agent can improve a post based on editorial feedback', function () {
    /** @var User $user */
    $user = User::factory()->create();
    $post = BlogPost::factory()->create([
        'locale' => 'pt_BR',
        'title' => 'Post original',
        'content' => 'Conteudo original',
    ]);

    actingAs($user);

    mock(BlogPostAgentService::class, function (MockInterface $mock): void {
        $mock->shouldReceive('improvePost')->once()->andReturn([
            'analysis' => 'Analise de melhoria',
            'action_plan' => 'Plano aplicado',
            'suggestion' => [
                'title' => 'Post melhorado',
                'slug' => 'post-melhorado',
                'excerpt' => 'Resumo melhorado',
                'content' => 'Conteudo melhorado',
                'seo_title' => 'SEO melhorado',
                'seo_description' => 'Descricao SEO melhorada',
            ],
        ]);
    });

    $response = postJson(route('admin.blog-post-agent.improve', $post), [
        'feedback' => 'Inclua exemplos praticos e CTA final.',
        'current_content' => 'Conteudo original para reconstruir.',
    ]);

    $response
        ->assertOk()
        ->assertJsonPath('suggestion.title', 'Post melhorado')
        ->assertJsonPath('analysis', 'Analise de melhoria');
});

test('blog post edit page is available for authenticated users', function () {
    /** @var User $user */
    $user = User::factory()->create();
    $post = BlogPost::factory()->create();

    actingAs($user);

    get(route('admin.blog-posts.edit', $post))
        ->assertOk()
        ->assertSee('"component":"admin\\/BlogPosts\\/Edit"', false);
});
