<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    /**
     * Show forgot password form.
     */
    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Send reset link.
     */
    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show reset password form.
     */
    public function showResetPassword(string $token): View
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Reset password.
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password_hash' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}