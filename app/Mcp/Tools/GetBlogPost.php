<?php

namespace App\Mcp\Tools;

use App\Mcp\Tools\Concerns\InteractsWithBlog;
use App\Models\BlogPost;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Retorna um post do blog com o conteúdo completo. Busque por id, ou por slug + locale.')]
class GetBlogPost extends Tool
{
    use InteractsWithBlog;

    public function handle(Request $request): Response
    {
        $id = $request->get('id');
        $slug = $request->get('slug');
        $locale = $request->get('locale', 'pt_BR');

        $post = BlogPost::query()
            ->when($id, fn ($q) => $q->whereKey($id))
            ->when(! $id && $slug, fn ($q) => $q->where('slug', $slug)->where('locale', $locale))
            ->first();

        if (! $post) {
            return Response::error('Post não encontrado. Informe um id válido ou slug + locale.');
        }

        return Response::json([
            'post' => $this->summarize($post, withContent: true),
        ]);
    }

    /**
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()->description('ID do post (preferencial).'),
            'slug' => $schema->string()->description('Slug do post (usar junto com locale).'),
            'locale' => $schema->string()->description('Locale do slug: pt_BR (padrão), es ou en.')->enum(['pt_BR', 'es', 'en']),
        ];
    }
}
