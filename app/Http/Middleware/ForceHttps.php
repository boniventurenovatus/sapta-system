<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    public function handle(Request $request, Closure $next): Response
    {
        // Kama tayari ni HTTPS (kupitia proxy au moja kwa moja), endelea
        if ($request->secure()) {
            return $next($request);
        }

        // Kama ni production na si HTTPS, redirect
        if (app()->environment('production')) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}