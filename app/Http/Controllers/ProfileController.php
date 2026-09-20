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

        if (
            !empty($user->profile_image) &&
            Storage::disk('public')->exists($user->profile_image)
        ) {
            Storage::disk('public')->delete($user->profile_image);
        }

        /*
        |--------------------------------------------------------------------------
        | Store new profile image
        |--------------------------------------------------------------------------
        */

        $path = $request
            ->file('profile_image')
            ->store('profile-images', 'public');

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

            if (
                Storage::disk('public')->exists(
                    $user->profile_image
                )
            ) {
                Storage::disk('public')->delete(
                    $user->profile_image
                );
            }

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
}

