<?php

use App\Http\Middleware\VerifyMcpToken;
use App\Mcp\Servers\BlogServer;
use App\Mcp\Servers\SeoServer;
use Laravel\Mcp\Facades\Mcp;

/*
|--------------------------------------------------------------------------
| MCP Servers
|--------------------------------------------------------------------------
|
| Cada servidor expõe dois transportes:
|  - local (stdio): dev, via `php artisan mcp:start <nome>`.
|  - web (HTTP):    produção, em POST /mcp/<nome>, protegido por token
|                   estático (header Authorization: Bearer <MCP_TOKEN>).
|
*/

Mcp::local('wmst-blog', BlogServer::class);

Mcp::web('mcp/wmst-blog', BlogServer::class)
    ->middleware(VerifyMcpToken::class);

// SEO/analytics (Search Console + GA4), somente leitura.
Mcp::local('wmst-seo', SeoServer::class);

Mcp::web('mcp/wmst-seo', SeoServer::class)
    ->middleware(VerifyMcpToken::class);
