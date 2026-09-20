<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // ============================================================
        // SUPER ADMIN — KILA KITU
        // ============================================================
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        // ============================================================
        // ADMIN — KILA KITU ISIPOKUWA super_admin PEKEE
        // ============================================================
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        // ============================================================
        // ANGALIA ROLES ZILIZOTAJWA
        // ============================================================
        if ($user->hasAnyRole($roles)) {
            return $next($request);
        }

        abort(403, 'Unauthorized. You do not have permission to access this page.');
    }
}