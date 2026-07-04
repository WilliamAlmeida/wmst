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

#[Description('Roda um relatório na Google Analytics Data API (GA4) para a propriedade wmst.com.br. Combine métricas (ex.: activeUsers, sessions, screenPageViews, conversions) com dimensões (ex.: date, country, deviceCategory, pagePath, sessionDefaultChannelGroup) num período.')]
class Ga4RunReport extends Tool
{
    use InteractsWithGoogle;

    public function handle(Request $request): Response
    {
        try {
            $property = (string) ($request->get('property_id') ?: $this->ga4Property());

            if ($property === '') {
                return Response::error('Propriedade GA4 não configurada. Defina GA4_PROPERTY_ID no .env ou passe property_id.');
            }

            $metrics = array_values((array) ($request->get('metrics') ?: ['activeUsers', 'sessions', 'screenPageViews']));
            $dimensions = array_values((array) ($request->get('dimensions') ?: []));
            $startDate = (string) ($request->get('start_date') ?: '28daysAgo');
            $endDate = (string) ($request->get('end_date') ?: 'today');
            $limit = max(1, min((int) $request->get('limit', 25), 1000));

            $body = array_filter([
                'dateRanges' => [['startDate' => $startDate, 'endDate' => $endDate]],
                'metrics' => array_map(fn (string $m): array => ['name' => $m], $metrics),
                'dimensions' => array_map(fn (string $d): array => ['name' => $d], $dimensions),
                'limit' => (string) $limit,
            ], fn ($value) => $value !== [] && $value !== null);

            $data = $this->google()->post(
                "https://analyticsdata.googleapis.com/v1beta/properties/{$property}:runReport",
                GoogleClient::SCOPE_ANALYTICS,
                $body,
            );

            $metricNames = array_map(fn (array $h): string => $h['name'], $data['metricHeaders'] ?? []);
            $dimensionNames = array_map(fn (array $h): string => $h['name'], $data['dimensionHeaders'] ?? []);

            $rows = array_map(function (array $row) use ($metricNames, $dimensionNames): array {
                $dimValues = array_map(fn (array $v): ?string => $v['value'] ?? null, $row['dimensionValues'] ?? []);
                $metValues = array_map(fn (array $v): ?string => $v['value'] ?? null, $row['metricValues'] ?? []);

                return [
                    'dimensions' => array_combine($dimensionNames, $dimValues) ?: [],
                    'metrics' => array_combine($metricNames, $metValues) ?: [],
                ];
            }, $data['rows'] ?? []);

            return Response::json([
                'property' => $property,
                'range' => ['start' => $startDate, 'end' => $endDate],
                'row_count' => $data['rowCount'] ?? count($rows),
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
            'metrics' => $schema->array()
                ->items($schema->string())
                ->description('Métricas GA4, ex.: ["activeUsers","sessions","screenPageViews","conversions"]. Padrão: activeUsers, sessions, screenPageViews.'),
            'dimensions' => $schema->array()
                ->items($schema->string())
                ->description('Dimensões GA4, ex.: ["date"], ["country"], ["pagePath"], ["sessionDefaultChannelGroup"]. Padrão: nenhuma (totais).'),
            'start_date' => $schema->string()->description('Início: YYYY-MM-DD ou relativo (ex.: 28daysAgo, 7daysAgo). Padrão: 28daysAgo.'),
            'end_date' => $schema->string()->description('Fim: YYYY-MM-DD ou relativo (ex.: today, yesterday). Padrão: today.'),
            'limit' => $schema->integer()->description('Máximo de linhas (1-1000, padrão 25).'),
            'property_id' => $schema->string()->description('ID numérico da propriedade GA4. Padrão: o configurado (544115334).'),
        ];
    }
}
