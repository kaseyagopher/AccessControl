<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware qui vérifie le rôle de l'utilisateur connecté.
 *
 * Utilisation dans les routes :
 *   Route::middleware(['auth', 'role:admin'])->group(...)
 *   Route::middleware(['auth', 'role:superviseur'])->group(...)
 *   Route::middleware(['auth', 'role:agent-de-security'])->group(...)
 *
 * Si l'utilisateur n'a pas le bon rôle → erreur 403.
 */
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (auth()->check() && auth()->user()->role === $role) {
            return $next($request);
        }

        abort(403, 'Accès refusé');
    }
}
