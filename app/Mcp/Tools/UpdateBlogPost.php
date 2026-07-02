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

#[Description('Atualiza um post existente do blog. Informe o id e apenas os campos que deseja alterar.')]
class UpdateBlogPost extends Tool
{
    use InteractsWithBlog;

    public function handle(Request $request): Response
    {
        $id = $request->get('id');

        $post = BlogPost::query()->find($id);

        if (! $post) {
            return Response::error("Post com id {$id} não encontrado.");
        }

        $updatable = ['title', 'content', 'locale', 'status', 'excerpt', 'slug', 'blog_category_id', 'seo_title', 'seo_description', 'featured_image_path', 'scheduled_for'];

        $data = [];
        if ($request->get('category_id') !== null) {
            $data['blog_category_id'] = $request->get('category_id');
        }
        foreach ($updatable as $field) {
            if ($request->get($field) !== null) {
                $data[$field] = $request->get($field);
            }
        }

        $validator = Validator::make($data, [
            'title' => ['sometimes', 'string', 'max:180'],
            'content' => ['sometimes', 'string'],
            'locale' => ['sometimes', Rule::in(['pt_BR', 'es', 'en'])],
            'status' => ['sometimes', Rule::enum(BlogPostStatus::class)],
            'excerpt' => ['sometimes', 'nullable', 'string'],
            'slug' => ['sometimes', 'string', 'max:180'],
            'blog_category_id' => ['sometimes', 'nullable', 'integer', 'exists:blog_categories,id'],
            'seo_title' => ['sometimes', 'nullable', 'string', 'max:180'],
            'seo_description' => ['sometimes', 'nullable', 'string'],
            'featured_image_path' => ['sometimes', 'nullable', 'string', 'max:255'],
            'scheduled_for' => ['sometimes', 'nullable', 'date'],
        ]);

        if ($validator->fails()) {
            return Response::error('Validação falhou: '.$validator->errors()->first());
        }

        $validated = $validator->validated();
        $locale = $validated['locale'] ?? $post->locale;

        if (array_key_exists('slug', $validated)) {
            $validated['slug'] = $this->uniqueSlug($validated['slug'], $locale, $post->id);
        } elseif (array_key_exists('title', $validated)) {
            $validated['slug'] = $this->uniqueSlug(Str::slug($validated['title']), $locale, $post->id);
        }

        $newStatus = $validated['status'] ?? $post->status->value;

        if ($newStatus === BlogPostStatus::Published->value && $post->published_at === null) {
            $validated['published_at'] = now();
        }

        if (array_key_exists('status', $validated) && $validated['status'] !== BlogPostStatus::Published->value) {
            $validated['published_at'] = null;
        }

        // Tags: só altera se enviado explicitamente.
        $tagIds = $request->get('tag_ids');

        $post->update($validated);

        if (is_array($tagIds)) {
            $post->tags()->sync($tagIds);
        }

        return Response::json([
            'message' => 'Post atualizado com sucesso.',
            'post' => $this->summarize($post->fresh()),
        ]);
    }

    /**
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()->description('ID do post a atualizar.')->required(),
            'title' => $schema->string()->description('Novo título.'),
            'content' => $schema->string()->description('Novo conteúdo (markdown/HTML).'),
            'locale' => $schema->string()->description('Idioma: pt_BR, es ou en.')->enum(['pt_BR', 'es', 'en']),
            'status' => $schema->string()->description('Status: draft, scheduled ou published.')->enum(['draft', 'scheduled', 'published']),
            'excerpt' => $schema->string()->description('Novo resumo.'),
            'slug' => $schema->string()->description('Novo slug (revalidado por locale).'),
            'category_id' => $schema->integer()->description('Novo ID de categoria.'),
            'tag_ids' => $schema->array()->items($schema->integer())->description('Substitui as tags do post pelos IDs informados.'),
            'seo_title' => $schema->string()->description('Novo título de SEO.'),
            'seo_description' => $schema->string()->description('Nova meta description.'),
            'featured_image_path' => $schema->string()->description('Novo caminho/URL da imagem de destaque.'),
            'scheduled_for' => $schema->string()->description('Data/hora ISO 8601 de agendamento.'),
        ];
    }
}
