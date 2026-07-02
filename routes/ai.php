<?php

use App\Http\Middleware\VerifyMcpToken;
use App\Mcp\Servers\BlogServer;
use Laravel\Mcp\Facades\Mcp;

/*
|--------------------------------------------------------------------------
| MCP Servers
|--------------------------------------------------------------------------
|
| Dois transportes para o mesmo BlogServer:
|  - local (stdio): dev, via `php artisan mcp:start wmst-blog`.
|  - web (HTTP):    produção, em POST /mcp/wmst-blog, protegido por token
|                   estático (header Authorization: Bearer <MCP_TOKEN>).
|
*/

Mcp::local('wmst-blog', BlogServer::class);

Mcp::web('mcp/wmst-blog', BlogServer::class)
    ->middleware(VerifyMcpToken::class);
