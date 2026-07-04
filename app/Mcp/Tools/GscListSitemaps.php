<?php

namespace App\Mcp\Tools;

use App\Mcp\Support\GoogleClient;
use App\Mcp\Tools\Concerns\InteractsWithGoogle;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;
use Throwable;

#[Description('Lista os sitemaps enviados ao Google Search Console para a propriedade, com contagem de URLs, avisos, erros e data do último processamento.')]
class GscListSitemaps extends Tool
{
    use InteractsWithGoogle;

    public function handle(Request $request): Response
    {
        try {
            $site = (string) ($request->get('site_url') ?: $this->gscSite());

            $data = $this->google()->get(
                'https://searchconsole.googleapis.com/webmasters/v3/sites/'.rawurlencode($site).'/sitemaps',
                GoogleClient::SCOPE_SEARCH_CONSOLE,
            );

            $sitemaps = array_map(fn (array $item): array => [
                'path' => $item['path'] ?? null,
                'last_submitted' => $item['lastSubmitted'] ?? null,
                'last_downloaded' => $item['lastDownloaded'] ?? null,
                'is_pending' => $item['isPending'] ?? null,
                'errors' => $item['errors'] ?? 0,
                'warnings' => $item['warnings'] ?? 0,
                'contents' => $item['contents'] ?? [],
            ], $data['sitemap'] ?? []);

            return Response::json([
                'site' => $site,
                'count' => count($sitemaps),
                'sitemaps' => $sitemaps,
            ]);
        } catch (Throwable $e) {
            return Response::error($e->getMessage());
        }
    }

    /**
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'site_url' => $schema->string()->description('Propriedade a consultar. Padrão: sc-domain:wmst.com.br.'),
        ];
    }
}
