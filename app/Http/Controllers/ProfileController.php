<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile.
     */
    public function show(): View
    {
        $user = Auth::user();

        $user->load('employee');

        return view('profile.show', [
            'user' => $user,
        ]);
    }

    /**
     * Show the profile edit form.
     */
    public function edit(): View
    {
        $user = Auth::user();

        $user->load('employee');

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the authenticated user's profile photo.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ], [
            'profile_image.required' => 'Please select a profile image.',
            'profile_image.image' => 'The selected file must be a valid image.',
            'profile_image.mimes' => 'Profile image must be JPG, JPEG, PNG, or WEBP.',
            'profile_image.max' => 'Profile image must not be larger than 2MB.',
        ]);

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Delete old profile image
        |--------------------------------------------------------------------------
        */

        $b2 = new \App\Services\B2StorageService();
        
        if (!empty($user->profile_image)) {
            $b2->delete($user->profile_image);
        }

        /*
        |--------------------------------------------------------------------------
        | Store new profile image
        |--------------------------------------------------------------------------
        */

        $b2 = new \App\Services\B2StorageService();
        $file = $request->file('profile_image');
        $storedName = \Str::random(40) . '.' . $file->getClientOriginalExtension();
        $path = 'profile-images/' . $storedName;
        
        $uploaded = $b2->putFile($path, $file->getRealPath(), $file->getMimeType());
        
        if (!$uploaded) {
            return back()->with('error', 'Profile image upload imeshindwa. Jaribu tena.')->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Update user
        |--------------------------------------------------------------------------
        */

        $user->profile_image = $path;
        $user->save();

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('profile.show')
            ->with(
                'success',
                'Your profile photo has been updated successfully.'
            );
    }

    /**
     * Remove the authenticated user's profile photo.
     */
    public function updateInfo(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'username' => 'required|string|max:100|unique:users,username,' . $user->id,
            'email' => 'required|email|max:150|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully.');
    }

    public function destroyPhoto(): RedirectResponse
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Delete profile image
        |--------------------------------------------------------------------------
        */

        if (!empty($user->profile_image)) {

            $b2 = new \App\Services\B2StorageService();
            $b2->delete($user->profile_image);

            /*
            |--------------------------------------------------------------------------
            | Clear database value
            |--------------------------------------------------------------------------
            */

            $user->profile_image = null;
            $user->save();
        }

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('profile.show')
            ->with(
                'success',
                'Your profile photo has been removed successfully.'
            );
    }

    /**
     * Show the change password form.
     */
    public function showChangePassword(): View
    {
        return view('profile.change-password');
    }

    /**
     * Update the authenticated user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Current password is required.',
            'password.required' => 'New password is required.',
            'password.min' => 'New password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $user = Auth::user();

        // Angalia kama current password ni sahihi
        if (!\Hash::check($validated['current_password'], $user->password_hash)) {
            return back()
                ->withErrors(['current_password' => 'Current password is incorrect.'])
                ->withInput();
        }

        // Angalia kama password mpya ni tofauti na ya zamani
        if (\Hash::check($validated['password'], $user->password_hash)) {
            return back()
                ->withErrors(['password' => 'New password must be different from current password.'])
                ->withInput();
        }

        // Update password
        $user->forceFill([
            'password_hash' => \Hash::make($validated['password']),
            'password_changed_at' => now(),
            'is_first_login' => false,
            'first_password_expires_at' => null,
            'credentials_expires_at' => null,
        ])->save();

        // Audit log
        \App\Models\UserActivityLog::log(
            action: 'password_changed',
            module: 'profile',
            description: 'User changed password',
        );

        return redirect()
            ->route('profile.show')
            ->with('success', 'Password imebadilishwa kikamilifu.');
    }

    /**
     * Serve profile image from B2 (proxy)
     */
    public function image(User $user)
    {
        if (!$user->profile_image) {
            abort(404, 'Picha haipo.');
        }

        $b2 = new B2StorageService();
        $contents = $b2->get($user->profile_image);

        if ($contents === null) {
            abort(404, 'Picha haipatikani B2.');
        }

        // Detect mime type
        $ext = strtolower(pathinfo($user->profile_image, PATHINFO_EXTENSION));
        $mimeType = match($ext) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            default => 'image/jpeg',
        };

        return response($contents)
            ->header('Content-Type', $mimeType)
            ->header('Content-Length', strlen($contents))
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
