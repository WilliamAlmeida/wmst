<?php

namespace App\Mcp\Tools;

use App\Models\BlogCategory;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Remove definitivamente uma categoria do blog. Os posts vinculados ficam sem categoria.')]
class DeleteBlogCategory extends Tool
{
    public function handle(Request $request): Response
    {
        $category = BlogCategory::query()->find($request->get('id'));

        if ($category === null) {
            return Response::error('Categoria não encontrada.');
        }

        $category->posts()->update(['blog_category_id' => null]);
        $category->delete();

        return Response::json([
            'message' => 'Categoria removida com sucesso.',
        ]);
    }

    /**
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()->description('ID da categoria a remover.')->required(),
        ];
    }
}
