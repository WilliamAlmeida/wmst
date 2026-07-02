<?php

namespace App\Mcp\Tools;

use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Lista as categorias e tags disponíveis do blog (id, name, slug, locale) para usar ao criar/atualizar posts.')]
class ListBlogTaxonomies extends Tool
{
    public function handle(Request $request): Response
    {
        $locale = $request->get('locale');

        $categories = BlogCategory::query()
            ->when($locale, fn ($q) => $q->where('locale', $locale))
            ->orderBy('name')
            ->get(['id', 'locale', 'name', 'slug']);

        $tags = BlogTag::query()
            ->when($locale, fn ($q) => $q->where('locale', $locale))
            ->orderBy('name')
            ->get(['id', 'locale', 'name', 'slug']);

        return Response::json([
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }

    /**
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'locale' => $schema->string()->description('Filtra por idioma: pt_BR, es ou en.')->enum(['pt_BR', 'es', 'en']),
        ];
    }
}
