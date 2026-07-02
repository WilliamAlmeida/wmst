<?php

namespace App\Mcp\Servers;

use App\Mcp\Tools\CreateBlogPost;
use App\Mcp\Tools\DeleteBlogPost;
use App\Mcp\Tools\GetBlogPost;
use App\Mcp\Tools\ListBlogPosts;
use App\Mcp\Tools\ListBlogTaxonomies;
use App\Mcp\Tools\UpdateBlogPost;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

#[Name('WMST Blog')]
#[Version('1.0.0')]
#[Instructions(<<<'TXT'
Servidor MCP para gerenciar o blog da WMST (Laravel).

Fluxo recomendado:
1. Use list-blog-taxonomies para descobrir os IDs de categorias e tags.
2. Use list-blog-posts / get-blog-post para inspecionar o conteúdo existente.
3. Use create-blog-post para publicar (title e content são obrigatórios; o slug é
   gerado por locale se omitido). Defina status = published para publicar na hora,
   scheduled (com scheduled_for) para agendar, ou draft para rascunho.
4. Use update-blog-post enviando o id e apenas os campos a alterar.
5. delete-blog-post remove definitivamente.

Locales suportados: pt_BR (padrão), es, en. O conteúdo aceita markdown ou HTML.
TXT)]
class BlogServer extends Server
{
    protected array $tools = [
        ListBlogTaxonomies::class,
        ListBlogPosts::class,
        GetBlogPost::class,
        CreateBlogPost::class,
        UpdateBlogPost::class,
        DeleteBlogPost::class,
    ];

    protected array $resources = [
        //
    ];

    protected array $prompts = [
        //
    ];
}
