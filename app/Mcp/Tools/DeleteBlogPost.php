<?php

namespace App\Mcp\Tools;

use App\Models\BlogPost;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Exclui permanentemente um post do blog pelo id. Ação destrutiva.')]
class DeleteBlogPost extends Tool
{
    public function handle(Request $request): Response
    {
        $id = $request->get('id');

        $post = BlogPost::query()->find($id);

        if (! $post) {
            return Response::error("Post com id {$id} não encontrado.");
        }

        $title = $post->title;
        $post->delete();

        return Response::json([
            'message' => "Post \"{$title}\" (id {$id}) excluído com sucesso.",
        ]);
    }

    /**
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()->description('ID do post a excluir.')->required(),
        ];
    }
}
