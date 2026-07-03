<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class McpController extends Controller
{
    /**
     * Página de documentação/integração do servidor MCP "WMST Blog".
     * Reúne endpoints reais, tools disponíveis, o token de produção e
     * snippets prontos de configuração para o Claude Code.
     */
    public function index(): InertiaResponse
    {
        $token = (string) config('services.mcp.token');
        $webEndpoint = url('mcp/wmst-blog');

        return Inertia::render('admin/Mcp/Index', [
            'localCommand' => 'php artisan mcp:start wmst-blog',
            'webEndpoint' => $webEndpoint,
            'tokenConfigured' => $token !== '',
            'token' => $token,
            'tools' => [
                [
                    'name' => 'list-blog-taxonomies',
                    'summary' => 'Lista categorias e tags (com IDs) por locale.',
                    'usage' => 'Comece por aqui para descobrir os IDs de categoria/tag antes de criar posts.',
                ],
                [
                    'name' => 'list-blog-posts',
                    'summary' => 'Lista posts existentes (filtra por locale/status).',
                    'usage' => 'Inspecione o que já existe para evitar duplicar temas.',
                ],
                [
                    'name' => 'get-blog-post',
                    'summary' => 'Retorna um post completo pelo id.',
                    'usage' => 'Leia o conteúdo atual antes de editar.',
                ],
                [
                    'name' => 'create-blog-post',
                    'summary' => 'Cria um post (title e content obrigatórios).',
                    'usage' => 'status: draft | published | scheduled (com scheduled_for). Slug é gerado se omitido.',
                ],
                [
                    'name' => 'update-blog-post',
                    'summary' => 'Atualiza um post enviando o id + campos a alterar.',
                    'usage' => 'Envie apenas os campos que mudam.',
                ],
                [
                    'name' => 'delete-blog-post',
                    'summary' => 'Remove um post definitivamente.',
                    'usage' => 'Ação irreversível.',
                ],
                [
                    'name' => 'create-blog-category',
                    'summary' => 'Cria uma categoria (name + locale).',
                    'usage' => 'Slug gerado se omitido. is_active padrão true.',
                ],
                [
                    'name' => 'update-blog-category',
                    'summary' => 'Atualiza uma categoria pelo id.',
                    'usage' => 'Envie apenas os campos que mudam.',
                ],
                [
                    'name' => 'delete-blog-category',
                    'summary' => 'Remove uma categoria definitivamente.',
                    'usage' => 'Os posts vinculados ficam sem categoria.',
                ],
                [
                    'name' => 'create-blog-tag',
                    'summary' => 'Cria uma tag (name + locale).',
                    'usage' => 'Slug gerado se omitido.',
                ],
                [
                    'name' => 'update-blog-tag',
                    'summary' => 'Atualiza uma tag pelo id.',
                    'usage' => 'Envie apenas os campos que mudam.',
                ],
                [
                    'name' => 'delete-blog-tag',
                    'summary' => 'Remove uma tag definitivamente.',
                    'usage' => 'Os vínculos com posts são desfeitos.',
                ],
            ],
        ]);
    }
}
