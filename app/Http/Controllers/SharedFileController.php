<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SharedFile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SharedFileController extends Controller
{
    public function index(Request $request)
    {
        $query = SharedFile::visibleTo(Auth::id())->with(['uploader']);

        // Filter by type
        if ($request->filled('type')) {
            $query->where('file_type', 'like', "%{$request->type}%");
        }

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $files = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total'      => SharedFile::visibleTo(Auth::id())->count(),
            'my_files'   => SharedFile::where('uploaded_by', Auth::id())->count(),
            'total_size' => SharedFile::visibleTo(Auth::id())->sum('file_size'),
            'shared'     => SharedFile::visibleTo(Auth::id())->where('visibility', 'team')->count(),
        ];

        return view('communication.shared-files', compact('files', 'stats'));
    }

    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->orderBy('username')->get();
        return view('communication.shared-files-create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file'        => 'required|file|max:51200', // max 50MB
            'visibility'  => 'nullable|in:private,team,public',
            'shared_with' => 'nullable|array',
            'shared_with.*' => 'integer|exists:users,id',
        ]);

        if (!$request->hasFile('file')) {
            return back()->with('error', 'No file selected.');
        }

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $path = $file->store('shared-files/' . date('Y/m'), 'public');

        SharedFile::create([
            'name'           => $originalName,
            'file_path'      => $path,
            'file_type'      => $file->getMimeType(),
            'file_size'      => $file->getSize(),
            'uploaded_by'    => Auth::id(),
            'visibility'     => $request->input('visibility', 'team'),
            'shared_with'    => $request->input('shared_with', []),
            'download_count' => 0,
        ]);

        return redirect()->route('communication.shared-files')
            ->with('success', 'File "' . $originalName . '" uploaded successfully.');
    }

    public function show(SharedFile $sharedFile)
    {
        // Angalia kama user ana ruhusa
        $userId = Auth::id();
        $canView = $sharedFile->uploaded_by === $userId
            || $sharedFile->visibility === 'public'
            || $sharedFile->visibility === 'team'
            || (is_array($sharedFile->shared_with) && in_array($userId, $sharedFile->shared_with));

        if (!$canView) {
            abort(403, 'You do not have permission to view this file.');
        }

        return view('communication.shared-files-show', compact('sharedFile'));
    }

    public function download(SharedFile $sharedFile)
    {
        // Angalia kama user ana ruhusa
        $userId = Auth::id();
        $canView = $sharedFile->uploaded_by === $userId
            || $sharedFile->visibility === 'public'
            || $sharedFile->visibility === 'team'
            || (is_array($sharedFile->shared_with) && in_array($userId, $sharedFile->shared_with));

        if (!$canView) {
            abort(403);
        }

        if (!Storage::disk('public')->exists($sharedFile->file_path)) {
            return back()->with('error', 'File not found on disk.');
        }

        $sharedFile->increment('download_count');

        return Storage::disk('public')->download($sharedFile->file_path, $sharedFile->name);
    }

    public function destroy(SharedFile $sharedFile)
    {
        if ($sharedFile->uploaded_by !== Auth::id()) {
            abort(403);
        }

        // Futa file kwenye disk
        if (Storage::disk('public')->exists($sharedFile->file_path)) {
            Storage::disk('public')->delete($sharedFile->file_path);
        }

        $sharedFile->delete();

        return redirect()->route('communication.shared-files')
            ->with('success', 'File deleted successfully.');
    }
}