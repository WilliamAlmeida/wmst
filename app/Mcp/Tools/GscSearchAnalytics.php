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

#[Description('Consulta o relatório de Desempenho do Google Search Console (cliques, impressões, CTR% e posição média). Agrupe por query, page, country, device ou date. Padrão: propriedade sc-domain:wmst.com.br, últimos ~28 dias.')]
class GscSearchAnalytics extends Tool
{
    use InteractsWithGoogle;

    public function handle(Request $request): Response
    {
        try {
            $site = (string) ($request->get('site_url') ?: $this->gscSite());
            // Dados do GSC têm ~2-3 dias de atraso; por isso o fim padrão é 3 dias atrás.
            $endDate = (string) ($request->get('end_date') ?: now()->subDays(3)->toDateString());
            $startDate = (string) ($request->get('start_date') ?: now()->subDays(31)->toDateString());
            $dimensions = array_values((array) ($request->get('dimensions') ?: ['query']));
            $rowLimit = max(1, min((int) $request->get('row_limit', 20), 1000));

            $body = array_filter([
                'startDate' => $startDate,
                'endDate' => $endDate,
                'dimensions' => $dimensions,
                'type' => $request->get('type'),
                'rowLimit' => $rowLimit,
            ], fn ($value) => $value !== null && $value !== []);

            $url = 'https://searchconsole.googleapis.com/webmasters/v3/sites/'
                .rawurlencode($site).'/searchAnalytics/query';

            $data = $this->google()->post($url, GoogleClient::SCOPE_SEARCH_CONSOLE, $body);

            $rows = array_map(function (array $row) use ($dimensions): array {
                $keys = $row['keys'] ?? [];

                return [
                    'keys' => count($keys) === count($dimensions) ? array_combine($dimensions, $keys) : $keys,
                    'clicks' => $row['clicks'] ?? 0,
                    'impressions' => $row['impressions'] ?? 0,
                    'ctr' => round(($row['ctr'] ?? 0) * 100, 2),
                    'position' => round($row['position'] ?? 0, 1),
                ];
            }, $data['rows'] ?? []);

            return Response::json([
                'site' => $site,
                'range' => ['start' => $startDate, 'end' => $endDate],
                'dimensions' => $dimensions,
                'row_count' => count($rows),
                'rows' => $rows,
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
            'start_date' => $schema->string()->description('Data inicial YYYY-MM-DD. Padrão: 31 dias atrás.'),
            'end_date' => $schema->string()->description('Data final YYYY-MM-DD. Padrão: 3 dias atrás (dados do GSC têm atraso).'),
            'dimensions' => $schema->array()
                ->items($schema->string()->enum(['query', 'page', 'country', 'device', 'date', 'searchAppearance']))
                ->description('Como agrupar os resultados. Padrão: ["query"].'),
            'type' => $schema->string()
                ->enum(['web', 'image', 'video', 'news', 'discover', 'googleNews'])
                ->description('Tipo de busca. Padrão: web.'),
            'row_limit' => $schema->integer()->description('Máximo de linhas (1-1000, padrão 20).'),
            'site_url' => $schema->string()->description('Sobrescreve a propriedade. Padrão: sc-domain:wmst.com.br.'),
        ];
    }
}
