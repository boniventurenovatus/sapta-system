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

        // ============================================================
        // SIMPLE LOGIN — checks zote zimeondolewa kwa muda
        // ============================================================
        $user = User::query()
            ->where('email', $login)
            ->orWhere('username', $login)
            ->first();

        if (!$user) {
            return back()
                ->withErrors(['email' => 'The email or password is incorrect.'])
                ->withInput($request->only('email'));
        }

        if (!Hash::check($password, $user->password_hash)) {
            return back()
                ->withErrors(['email' => 'The email or password is incorrect.'])
                ->withInput($request->only('email'));
        }

        Auth::login($user, $remember);
        $request->session()->regenerate();

        $user->forceFill([
            'last_login_at' => now(),
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ])->save();

        UserActivityLog::log(
            action: 'login',
            module: 'auth',
            description: 'User logged in'
        );

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