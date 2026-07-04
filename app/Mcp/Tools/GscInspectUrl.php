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

#[Description('Inspeciona uma URL específica no Google Search Console: status de indexação, resultado do último rastreamento, canonical escolhido pelo Google, cobertura e problemas de mobile/rich results. Equivale à "Inspeção de URL" do painel.')]
class GscInspectUrl extends Tool
{
    use InteractsWithGoogle;

    public function handle(Request $request): Response
    {
        try {
            $inspectionUrl = (string) $request->get('inspection_url');

            if ($inspectionUrl === '') {
                return Response::error('Informe inspection_url (a URL completa a inspecionar).');
            }

            $data = $this->google()->post(
                'https://searchconsole.googleapis.com/v1/urlInspection/index:inspect',
                GoogleClient::SCOPE_SEARCH_CONSOLE,
                array_filter([
                    'inspectionUrl' => $inspectionUrl,
                    'siteUrl' => (string) ($request->get('site_url') ?: $this->gscSite()),
                    'languageCode' => (string) ($request->get('language_code') ?: 'pt-BR'),
                ]),
            );

            return Response::json($data['inspectionResult'] ?? $data);
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
            'inspection_url' => $schema->string()
                ->description('URL completa a inspecionar, ex.: https://wmst.com.br/blog/meu-post.')
                ->required(),
            'site_url' => $schema->string()->description('Propriedade dona da URL. Padrão: sc-domain:wmst.com.br.'),
            'language_code' => $schema->string()->description('Código de idioma BCP-47 para as mensagens. Padrão: pt-BR.'),
        ];
    }
}
