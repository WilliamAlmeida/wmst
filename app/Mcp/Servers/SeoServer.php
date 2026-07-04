<?php

namespace App\Mcp\Servers;

use App\Mcp\Tools\Ga4RunReport;
use App\Mcp\Tools\GscInspectUrl;
use App\Mcp\Tools\GscListSitemaps;
use App\Mcp\Tools\GscSearchAnalytics;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

#[Name('WMST SEO')]
#[Version('1.0.0')]
#[Instructions(<<<'TXT'
Servidor MCP de SEO/analytics da WMST — leitura do Google Search Console e do
Google Analytics (GA4) via service account. Todas as tools são somente leitura.

Search Console (propriedade padrão: sc-domain:wmst.com.br):
- gsc-search-analytics: relatório de Desempenho (cliques, impressões, CTR, posição)
  agrupado por query/page/country/device/date. Lembre que o site é uma propriedade
  de DOMÍNIO, então inclui subdomínios de clientes; filtre por page quando quiser só
  o institucional (https://wmst.com.br/...).
- gsc-inspect-url: status de indexação de uma URL específica.
- gsc-list-sitemaps: sitemaps enviados e seu status.

Analytics (propriedade GA4 padrão: 544115334):
- ga4-report: runReport com métricas + dimensões num período. Datas aceitam
  YYYY-MM-DD ou relativas (28daysAgo, today).

Observações:
- Datas do Search Console têm ~2-3 dias de atraso; o padrão das tools já considera isso.
- Se aparecer erro de credenciais, a chave JSON da service account não está no caminho
  de GOOGLE_SA_CREDENTIALS ou a SA não foi adicionada como usuário na propriedade.
TXT)]
class SeoServer extends Server
{
    protected array $tools = [
        GscSearchAnalytics::class,
        GscInspectUrl::class,
        GscListSitemaps::class,
        Ga4RunReport::class,
    ];

    protected array $resources = [
        //
    ];

    protected array $prompts = [
        //
    ];
}
