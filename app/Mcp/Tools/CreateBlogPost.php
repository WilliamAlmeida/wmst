<?php

namespace App\Mcp\Tools;

use App\Enums\BlogPostStatus;
use App\Mcp\Tools\Concerns\InteractsWithBlog;
use App\Models\BlogPost;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Cria um novo post do blog da WMST. Informe título e conteúdo (markdown/HTML). O slug é gerado automaticamente por locale se não for enviado. Use list-blog-taxonomies para descobrir category_id e tag_ids.')]
class CreateBlogPost extends Tool
{
    use InteractsWithBlog;

    public function handle(Request $request): Response
    {
        $authorId = $this->resolveAuthorId();

        if ($authorId === null) {
            return Response::error('Nenhum usuário cadastrado para ser autor do post. Crie um usuário primeiro.');
        }

        $data = [
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'locale' => $request->get('locale', 'pt_BR'),
            'status' => $request->get('status', BlogPostStatus::Draft->value),
            'excerpt' => $request->get('excerpt'),
            'slug' => $request->get('slug'),
            'blog_category_id' => $request->get('category_id'),
            'seo_title' => $request->get('seo_title'),
            'seo_description' => $request->get('seo_description'),
            'featured_image_path' => $request->get('featured_image_path'),
            'scheduled_for' => $request->get('scheduled_for'),
            'tag_ids' => $request->get('tag_ids', []),
        ];

        $validator = Validator::make($data, [
            'title' => ['required', 'string', 'max:180'],
            'content' => ['required', 'string'],
            'locale' => ['required', Rule::in(['pt_BR', 'es', 'en'])],
            'status' => ['required', Rule::enum(BlogPostStatus::class)],
            'excerpt' => ['nullable', 'string'],
            'slug' => ['nullable', 'string', 'max:180'],
            'blog_category_id' => ['nullable', 'integer', 'exists:blog_categories,id'],
            'seo_title' => ['nullable', 'string', 'max:180'],
            'seo_description' => ['nullable', 'string'],
            'featured_image_path' => ['nullable', 'string', 'max:255'],
            'scheduled_for' => ['nullable', 'date', 'required_if:status,'.BlogPostStatus::Scheduled->value],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['integer', 'exists:blog_tags,id'],
        ]);

        if ($validator->fails()) {
            return Response::error('Validação falhou: '.$validator->errors()->first());
        }

        $validated = $validator->validated();
        $tagIds = $validated['tag_ids'] ?? [];
        unset($validated['tag_ids']);

        $validated['slug'] = $this->uniqueSlug(
            $validated['slug'] ?? Str::slug($validated['title']),
            $validated['locale'],
        );
        $validated['author_id'] = $authorId;

        $validated['published_at'] = $validated['status'] === BlogPostStatus::Published->value
            ? now()
            : null;

        $post = BlogPost::query()->create($validated);
        $post->tags()->sync($tagIds);

        return Response::json([
            'message' => 'Post criado com sucesso.',
            'post' => $this->summarize($post),
        ]);
    }

    /**
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'title' => $schema->string()->description('Título do post (máx. 180 caracteres).')->required(),
            'content' => $schema->string()->description('Conteúdo do post em markdown ou HTML.')->required(),
            'locale' => $schema->string()->description('Idioma: pt_BR, es ou en.')->enum(['pt_BR', 'es', 'en']),
            'status' => $schema->string()->description('Status: draft, scheduled ou published.')->enum(['draft', 'scheduled', 'published']),
            'excerpt' => $schema->string()->description('Resumo curto usado em listagens e SEO.'),
            'slug' => $schema->string()->description('Slug opcional; gerado a partir do título se omitido.'),
            'category_id' => $schema->integer()->description('ID da categoria (ver list-blog-taxonomies).'),
            'tag_ids' => $schema->array()->items($schema->integer())->description('IDs das tags (ver list-blog-taxonomies).'),
            'seo_title' => $schema->string()->description('Título para SEO (máx. 180).'),
            'seo_description' => $schema->string()->description('Meta description para SEO.'),
            'featured_image_path' => $schema->string()->description('Caminho/URL da imagem de destaque.'),
            'scheduled_for' => $schema->string()->description('Data/hora ISO 8601 quando status = scheduled.'),
        ];
    }
}
