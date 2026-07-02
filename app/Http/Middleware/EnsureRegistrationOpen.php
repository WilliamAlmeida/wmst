<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * O cadastro público só fica disponível enquanto não existir nenhum usuário.
 * Assim que o primeiro usuário é registrado, as rotas de registro passam a
 * responder 404 — novos usuários passam a ser criados apenas pelo painel admin.
 */
class EnsureRegistrationOpen
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('register') && User::query()->exists()) {
            abort(404);
        }

        return $next($request);
    }
}
