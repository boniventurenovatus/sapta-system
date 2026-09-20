<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Models\UserActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $login = $request->validated('email');
        $password = $request->validated('password');
        $remember = (bool) $request->validated('remember', false);

        $user = User::query()
            ->where('email', $login)->orWhere('username', $login)
            ->first();

        if (! $user) {
            return back()
                ->withErrors([
                    'email' => 'The email or password is incorrect.',
                ])
                ->withInput($request->only('email'));
        }

        // Check 1: user account_status
        if ($user->account_status !== 'active') {
            $statusMessage = match ($user->account_status) {
                'inactive'   => 'Your account is inactive. Please contact the administrator.',
                'suspended'  => 'Your account is suspended. Please contact the administrator.',
                'terminated' => 'Your account has been terminated. Please contact the administrator.',
                'locked'     => 'Your account is locked. Please try again later or contact the administrator.',
                default      => 'Your account is not active. Please contact the administrator.',
            };

            return back()
                ->withErrors(['email' => $statusMessage])
                ->withInput($request->only('email'));
        }

        // Check 2: employee employment_status
        if ($user->employee && $user->employee->employment_status !== 'active') {
            $empStatus = $user->employee->employment_status;

            $empMessage = match ($empStatus) {
                'suspended'  => 'Your employment is currently suspended. Please contact HR for more information.',
                'terminated' => 'Your employment has been terminated. Please contact HR for more information.',
                'inactive'   => 'Your employment is inactive. Please contact HR for more information.',
                'on_leave'   => 'Your employment is on leave. Please contact HR for more information.',
                default      => 'Your employment status is not active. Please contact HR.',
            };

            return back()
                ->withErrors(['email' => $empMessage])
                ->withInput($request->only('email'));
        }

        if (! Hash::check($password, $user->password_hash)) {
            return back()
                ->withErrors([
                    'email' => 'The email or password is incorrect.',
                ])
                ->withInput($request->only('email'));
        }

        Auth::login($user, $remember);

        $request->session()->regenerate();

        UserActivityLog::log(
            action: 'login',
            module: 'auth',
            description: 'User logged in'
        );

        $user->forceFill([
            'last_login_at' => now(),
            'failed_login_attempts' => 0,
        ])->save();

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            UserActivityLog::log(
                action: 'logout',
                module: 'auth',
                description: 'User logged out'
            );
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
