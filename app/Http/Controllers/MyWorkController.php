<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class MyWorkController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employeeId = $user->employee_id;

        $stats = [
            'drafts'        => 0,
            'my_tasks'      => Task::where('assigned_to', $employeeId)->count(),
            'pending_tasks' => Task::where('assigned_to', $employeeId)->where('status', '!=', 'done')->count(),
            'approvals'     => 0,
        ];

        $recentTasks = Task::where('assigned_to', $employeeId)
            ->with('project')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('my-work.index', compact('stats', 'recentTasks'));
    }

    public function drafts()
    {
        return view('my-work.drafts');
    }

    public function tasks(Request $request)
    {
        $user = Auth::user();
        $query = Task::where('assigned_to', $user->employee_id)->with('project');

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $tasks = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('my-work.tasks', compact('tasks'));
    }

    public function approvals()
    {
        return view('my-work.approvals');
    }
}