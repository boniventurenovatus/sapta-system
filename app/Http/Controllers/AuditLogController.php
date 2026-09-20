<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('description', 'LIKE', "%{$s}%")
                  ->orWhere('model_type', 'LIKE', "%{$s}%")
                  ->orWhereHas('user', function ($u) use ($s) {
                      $u->where('username', 'LIKE', "%{$s}%");
                  });
            });
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $logs = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => AuditLog::count(),
            'today' => AuditLog::whereDate('created_at', today())->count(),
            'created' => AuditLog::where('action', 'created')->count(),
            'updated' => AuditLog::where('action', 'updated')->count(),
            'deleted' => AuditLog::where('action', 'deleted')->count(),
        ];

        $users = User::orderBy('username')->get();

        return view('audit-logs.index', compact('logs', 'stats', 'users'));
    }

    public function show(AuditLog $auditLog)
    {
        $auditLog->load('user');
        return view('audit-logs.show', compact('auditLog'));
    }

    public function clear()
    {
        AuditLog::truncate();
        return redirect()->route('audit-logs.index')->with('success', 'All audit logs cleared.');
    }

    public function destroy(AuditLog $auditLog)
    {
        $auditLog->delete();
        return redirect()->route('audit-logs.index')->with('success', 'Log entry deleted.');
    }
}
