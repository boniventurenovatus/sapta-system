<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // 1. Angalia user account_status
        if ($user->account_status !== 'active') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Your account has been deactivated. Please contact the administrator.',
                ]);
        }

        // 2. Angalia employee employment_status
        if ($user->employee && $user->employee->employment_status !== 'active') {
            $status = $user->employee->employment_status;
            $message = match ($status) {
                'suspended'  => 'Your account is suspended. Please contact HR.',
                'terminated' => 'Your account has been terminated. Please contact HR.',
                'inactive'   => 'Your account is not active. Please contact HR.',
                'on_leave'   => 'Your account is on leave. Please contact HR.',
                default      => 'Your account is not active. Please contact HR.',
            };

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors(['email' => $message]);
        }

        return $next($request);
    }
}