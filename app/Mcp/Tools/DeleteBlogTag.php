<?php

namespace App\Mcp\Tools;

use App\Models\BlogTag;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Remove definitivamente uma tag do blog. Os vínculos com posts são desfeitos.')]
class DeleteBlogTag extends Tool
{
    public function handle(Request $request): Response
    {
        $tag = BlogTag::query()->find($request->get('id'));

        if ($tag === null) {
            return Response::error('Tag não encontrada.');
        }

        $tag->posts()->detach();
        $tag->delete();

        return Response::json([
            'message' => 'Tag removida com sucesso.',
        ]);
    }

    /**
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()->description('ID da tag a remover.')->required(),
        ];
    }
}
