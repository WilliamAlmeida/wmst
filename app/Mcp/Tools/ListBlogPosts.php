<?php

namespace App\Mcp\Tools;

use App\Mcp\Tools\Concerns\InteractsWithBlog;
use App\Models\BlogPost;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Lista posts do blog com filtros opcionais por locale, status e busca por título. Retorna dados resumidos (sem o conteúdo completo).')]
class ListBlogPosts extends Tool
{
    use InteractsWithBlog;

    public function handle(Request $request): Response
    {
        $limit = (int) $request->get('limit', 20);
        $limit = max(1, min($limit, 100));

        $posts = BlogPost::query()
            ->with(['category:id,name', 'tags:id,name'])
            ->when($request->get('locale'), fn ($q, $locale) => $q->where('locale', $locale))
            ->when($request->get('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->get('search'), fn ($q, $search) => $q->where('title', 'like', "%{$search}%"))
            ->latest()
            ->limit($limit)
            ->get();

        return Response::json([
            'count' => $posts->count(),
            'posts' => $posts->map(fn (BlogPost $post): array => $this->summarize($post))->all(),
        ]);
    }

    /**
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'locale' => $schema->string()->description('Filtra por idioma: pt_BR, es ou en.')->enum(['pt_BR', 'es', 'en']),
            'status' => $schema->string()->description('Filtra por status: draft, scheduled ou published.')->enum(['draft', 'scheduled', 'published']),
            'search' => $schema->string()->description('Busca por parte do título.'),
            'limit' => $schema->integer()->description('Máximo de posts a retornar (1-100, padrão 20).'),
        ];
    }
}
