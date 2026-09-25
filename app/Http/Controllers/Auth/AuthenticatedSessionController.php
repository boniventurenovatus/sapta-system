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
use Illuminate\Support\Facades\RateLimiter;
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
        // 1. RATE LIMITING — 5 attempts kwa dakika 1
        // ============================================================
        $key = 'login:' . strtolower($login) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()
                ->withErrors(['email' => "Too many login attempts. Please try again in {$seconds} seconds."])
                ->withInput($request->only('email'));
        }

        // ============================================================
        // 2. TAFUTA USER
        // ============================================================
        $user = User::query()
            ->where('email', $login)
            ->orWhere('username', $login)
            ->first();

        // ============================================================
        // 3. USER HAIPO — usifichue
        // ============================================================
        if (! $user) {
            RateLimiter::hit($key, 60);
            return back()
                ->withErrors(['email' => 'The email or password is incorrect.'])
                ->withInput($request->only('email'));
        }

        // ============================================================
        // 4. ACCOUNT LOCKOUT CHECK
        // ============================================================
        if ($user->locked_until && now()->lt($user->locked_until)) {
            $minutes = now()->diffInMinutes($user->locked_until);
            return back()
                ->withErrors(['email' => "Account imefungwa. Jaribu tena baada ya dakika {$minutes}."])
                ->withInput($request->only('email'));
        }

        // Kama lockout imeisha, clear
        if ($user->locked_until && now()->gte($user->locked_until)) {
            $user->forceFill([
                'locked_until' => null,
                'failed_login_attempts' => 0,
            ])->save();
        }

        // ============================================================
        // 5. ACCOUNT STATUS CHECK
        // ============================================================
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

        // ============================================================
        // 6. EMPLOYEE STATUS CHECK
        // ============================================================
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

        // ============================================================
        // 7. PASSWORD CHECK
        // ============================================================
        if (! Hash::check($password, $user->password_hash)) {
            $user->increment('failed_login_attempts');
            $user->refresh();

            if ($user->failed_login_attempts >= 5) {
                $user->forceFill(['locked_until' => now()->addMinutes(15)])->save();
                UserActivityLog::log(
                    action: 'account_locked',
                    module: 'auth',
                    description: "Account locked after {$user->failed_login_attempts} failed attempts"
                );
            }

            RateLimiter::hit($key, 60);

            return back()
                ->withErrors(['email' => 'The email or password is incorrect.'])
                ->withInput($request->only('email'));
        }

        // ============================================================
        // 8. CREDENTIALS EXPIRY (first login)
        // ============================================================
        if ($user->is_first_login && $user->first_password_expires_at && now()->gt($user->first_password_expires_at)) {
            return back()
                ->withErrors(['email' => 'Credentials zako zime-expire. Tafadhali wasiliana na Admin.'])
                ->withInput($request->only('email'));
        }

        // ============================================================
        // 9. LOGIN
        // ============================================================
        Auth::login($user, $remember);
        $request->session()->regenerate();

        // ============================================================
        // 10. RESET FAILED ATTEMPTS + AUDIT LOG
        // ============================================================
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

        RateLimiter::clear($key);

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
