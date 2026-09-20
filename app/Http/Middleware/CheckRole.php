<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = auth()->user();

        // ============================================================
        // 1. Kama hajaingia — redirect login
        // ============================================================
        if (!$user) {
            return redirect()->route('login');
        }

        // ============================================================
        // 2. SUPER ADMIN BYPASS — anaweza kila kitu
        // ============================================================
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        // ============================================================
        // 3. ADMIN BYPASS — anaweza kila kitu isipokuwa super_admin
        // ============================================================
        if ($user->hasRole('admin')) {
            // Admin anaweza kila kitu isipokuwa settings za super_admin
            if (!in_array('super_admin', $roles)) {
                return $next($request);
            }
        }

        // ============================================================
        // 4. Angalia roles zilizotajwa
        // ============================================================
        if ($user->hasAnyRole($roles)) {
            return $next($request);
        }

        // ============================================================
        // 5. Kataa — 403
        // ============================================================
        abort(403, 'Unauthorized. You do not have permission to access this page.');
    }
}