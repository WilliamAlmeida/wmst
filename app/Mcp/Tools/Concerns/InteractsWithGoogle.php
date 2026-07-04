<?php

namespace App\Mcp\Tools\Concerns;

use App\Mcp\Support\GoogleClient;

trait InteractsWithGoogle
{
    protected function google(): GoogleClient
    {
        return GoogleClient::make();
    }

    /**
     * Propriedade padrão do Search Console (ex.: sc-domain:wmst.com.br).
     */
    protected function gscSite(): string
    {
        return (string) config('services.google.gsc_site', 'sc-domain:wmst.com.br');
    }

    /**
     * ID numérico da propriedade GA4 (ex.: 544115334).
     */
    protected function ga4Property(): string
    {
        return (string) config('services.google.ga4_property');
    }
}
