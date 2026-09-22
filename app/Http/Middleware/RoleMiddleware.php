<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $userRoles = $user->roles()->pluck('code')->toArray();

        if (empty(array_intersect($roles, $userRoles))) {
            abort(403, 'Hauna ruhusa kufikia ukurasa huu.');
        }

        return $next($request);
    }
}