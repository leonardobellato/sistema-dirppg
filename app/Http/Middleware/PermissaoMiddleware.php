<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class PermissaoMiddleware
{
    public function handle(Request $request, Closure $next, ...$tipos)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Verifica se o usuário tem uma das roles passadas
        if (!in_array(Auth::user()->tipo, $tipos)) {
            abort(403, 'Você não tem permissão para acessar esta página.');
        }

        return $next($request);
    }
}
