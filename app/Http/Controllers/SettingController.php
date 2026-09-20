<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $groups = [
            'general' => Setting::group('general')->get(),
            'system' => Setting::group('system')->get(),
            'security' => Setting::group('security')->get(),
            'notifications' => Setting::group('notifications')->get(),
        ];

        return view('settings.index', compact('groups'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token', '_method');

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}
