<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Autenticação leve do servidor MCP web (produção): exige um token estático
 * no header `Authorization: Bearer <MCP_TOKEN>`. Sem pacote/tabela extra —
 * o token vem de config('services.mcp.token') (.env MCP_TOKEN).
 */
class VerifyMcpToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = (string) config('services.mcp.token');
        $provided = (string) $request->bearerToken();

        if ($expected === '' || $provided === '' || ! hash_equals($expected, $provided)) {
            abort(401, 'Token MCP inválido ou ausente.');
        }

        return $next($request);
    }
}
