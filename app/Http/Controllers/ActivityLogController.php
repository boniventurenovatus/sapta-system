<?php

namespace App\Http\Controllers;

use App\Models\UserActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = UserActivityLog::with('user')->latest();

        // Filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(30)->withQueryString();

        \Log::info('=== ACTIVITY LOG DEBUG ===', [
            'total' => $logs->total(),
            'items' => $logs->count(),
            'totalLogs_var' => UserActivityLog::count(),
            'first_log' => $logs->first() ? $logs->first()->action : 'NONE',
        ]);

        $users = User::orderBy('username')->get(['id', 'username']);
        $actions = UserActivityLog::select('action')->distinct()->pluck('action');
        $modules = UserActivityLog::select('module')->distinct()->whereNotNull('module')->pluck('module');

        $totalLogs = UserActivityLog::count();
        $todayLogs = UserActivityLog::whereDate('created_at', today())->count();
        $uniqueUsers = UserActivityLog::distinct('user_id')->count('user_id');

        return view('activity-logs.index', compact(
            'logs', 'users', 'actions', 'modules',
            'totalLogs', 'todayLogs', 'uniqueUsers'
        ));
    }

    public function show(UserActivityLog $log)
    {
        $log->load('user', 'subject');
        return view('activity-logs.show', compact('log'));
    }

    public function userActivity(User $user)
    {
        $logs = UserActivityLog::where('user_id', $user->id)
            ->latest()
            ->paginate(30);

        return view('activity-logs.user', compact('user', 'logs'));
    }
}