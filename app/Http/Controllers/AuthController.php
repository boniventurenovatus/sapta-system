<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Show login page
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Login user
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
                'string',
            ],
        ]);

        $user = User::where(
            'email',
            $credentials['email']
        )->first();

        /*
        |--------------------------------------------------------------------------
        | Check account
        |--------------------------------------------------------------------------
        */

        if (!$user) {
            return back()
                ->withErrors([
                    'email' => 'The email or password is incorrect.',
                ])
                ->withInput(
                    $request->only('email')
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Check account status
        |--------------------------------------------------------------------------
        */

        if ($user->account_status !== 'active') {
            return back()
                ->withErrors([
                    'email' => 'This account is not active.',
                ])
                ->withInput(
                    $request->only('email')
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Check account lock
        |--------------------------------------------------------------------------
        */

        if (
            $user->locked_until &&
            $user->locked_until->isFuture()
        ) {
            return back()
                ->withErrors([
                    'email' =>
                        'This account is temporarily locked. Please try again later.',
                ])
                ->withInput(
                    $request->only('email')
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Check password
        |--------------------------------------------------------------------------
        */

        if (!Hash::check(
            $credentials['password'],
            $user->password_hash
        )) {

            $user->increment('failed_login_attempts');

            return back()
                ->withErrors([
                    'email' => 'The email or password is incorrect.',
                ])
                ->withInput(
                    $request->only('email')
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Successful login
        |--------------------------------------------------------------------------
        */

        $user->failed_login_attempts = 0;
        $user->locked_until = null;
        $user->last_login_at = now();
        $user->save();

        Auth::login(
            $user,
            $request->boolean('remember')
        );

        $request->session()->regenerate();

        return redirect()
            ->intended(route('dashboard'))
            ->with(
                'success',
                'Welcome back!'
            );
    }

    /**
     * Logout user
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with(
                'success',
                'You have been logged out successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | FORGOT PASSWORD
    |--------------------------------------------------------------------------
    */

    /**
     * Show forgot password form
     */
    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Generate password reset link
     *
     * DEVELOPMENT MODE:
     * The reset URL is displayed directly on the page.
     */
    public function sendResetLink(
        Request $request
    ): RedirectResponse {

        $request->validate([
            'email' => [
                'required',
                'email',
            ],
        ]);

        $email = $request->input('email');

        $user = User::where(
            'email',
            $email
        )->first();

        if (!$user) {
            return back()->with(
                'status',
                'If an account exists for this email, a password reset link has been generated.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Generate secure token
        |--------------------------------------------------------------------------
        */

        $token = Str::random(64);

        /*
        |--------------------------------------------------------------------------
        | Remove old reset token
        |--------------------------------------------------------------------------
        */

        DB::table('password_reset_tokens')
            ->where('email', $email)
            ->delete();

        /*
        |--------------------------------------------------------------------------
        | Store hashed token
        |--------------------------------------------------------------------------
        */

        DB::table('password_reset_tokens')
            ->insert([
                'email' => $email,
                'token' => Hash::make($token),
                'created_at' => Carbon::now(),
            ]);

        /*
        |--------------------------------------------------------------------------
        | Create reset URL
        |--------------------------------------------------------------------------
        */

        $resetUrl = route(
            'password.reset',
            [
                'token' => $token,
                'email' => $email,
            ]
        );

        return back()
            ->with(
                'status',
                'A password reset link has been generated.'
            )
            ->with(
                'reset_url',
                $resetUrl
            );
    }

    /**
     * Show reset password form
     */
    public function showResetPassword(
        Request $request,
        string $token
    ): View|RedirectResponse {

        $email = $request->query('email');

        if (!$email) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => 'Invalid password reset link.',
                ]);
        }

        return view(
            'auth.reset-password',
            [
                'token' => $token,
                'email' => $email,
            ]
        );
    }

    /**
     * Reset password
     */
    public function resetPassword(
        Request $request
    ): RedirectResponse {

        $validated = $request->validate([
            'token' => [
                'required',
                'string',
            ],

            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Find reset token
        |--------------------------------------------------------------------------
        */

        $resetRecord = DB::table('password_reset_tokens')
            ->where(
                'email',
                $validated['email']
            )
            ->first();

        if (!$resetRecord) {
            return back()
                ->withErrors([
                    'email' =>
                        'This password reset link is invalid or has expired.',
                ])
                ->withInput(
                    $request->only('email')
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Verify token
        |--------------------------------------------------------------------------
        */

        if (!Hash::check(
            $validated['token'],
            $resetRecord->token
        )) {
            return back()
                ->withErrors([
                    'email' =>
                        'This password reset link is invalid or has expired.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Check token expiration
        |--------------------------------------------------------------------------
        */

        if (
            Carbon::parse(
                $resetRecord->created_at
            )->addMinutes(60)->isPast()
        ) {
            DB::table('password_reset_tokens')
                ->where(
                    'email',
                    $validated['email']
                )
                ->delete();

            return back()
                ->withErrors([
                    'email' =>
                        'This password reset link has expired. Please request a new one.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Find user
        |--------------------------------------------------------------------------
        */

        $user = User::where(
            'email',
            $validated['email']
        )->first();

        if (!$user) {
            return back()
                ->withErrors([
                    'email' =>
                        'Unable to reset this account password.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Update password
        |--------------------------------------------------------------------------
        */

        $user->password_hash = Hash::make(
            $validated['password']
        );

        $user->password_changed_at = Carbon::now();

        $user->is_first_login = false;

        $user->failed_login_attempts = 0;

        $user->locked_until = null;

        $user->save();

        /*
        |--------------------------------------------------------------------------
        | Delete used token
        |--------------------------------------------------------------------------
        */

        DB::table('password_reset_tokens')
            ->where(
                'email',
                $validated['email']
            )
            ->delete();

        /*
        |--------------------------------------------------------------------------
        | Return to login
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('login')
            ->with(
                'status',
                'Your password has been reset successfully. You can now sign in with your new password.'
            );
    }
}

