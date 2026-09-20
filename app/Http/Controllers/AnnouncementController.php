<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::published()
            ->with(['creator'])
            ->orderByDesc('is_pinned')
            ->orderBy('published_at', 'desc')
            ->paginate(20);

        return view('communication.announcements', compact('announcements'));
    }

    public function create()
    {
        return view('communication.announcements-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'body'         => 'required|string|max:5000',
            'priority'     => 'required|in:low,normal,high,urgent',
            'audience'     => 'required|in:all,department,role,custom',
            'is_pinned'    => 'boolean',
            'expires_at'   => 'nullable|date|after:today',
        ]);

        Announcement::create([
            'title'        => $validated['title'],
            'body'         => $validated['body'],
            'priority'     => $validated['priority'],
            'audience'     => $validated['audience'],
            'is_pinned'    => $request->boolean('is_pinned'),
            'created_by'   => Auth::id(),
            'published_at' => now(),
            'expires_at'   => $validated['expires_at'] ?? null,
        ]);

        return redirect()->route('communication.announcements')
            ->with('success', 'Announcement published successfully.');
    }

    public function show(Announcement $announcement)
    {
        return view('communication.announcements-show', compact('announcement'));
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('communication.announcements')
            ->with('success', 'Announcement deleted successfully.');
    }
}