<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\Employee;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['project', 'assignee']);
        $user = auth()->user();

        // ================================================================
        // ROLE-BASED: Staff na Manager wanaona tasks zao tu
        // ================================================================
        $roleCodes = $user->roles->pluck('code')->toArray();
        $isStaff = in_array('staff', $roleCodes);
        $isManager = in_array('manager', $roleCodes);
        $isAdmin = in_array('super_admin', $roleCodes) || in_array('admin', $roleCodes) || in_array('director', $roleCodes);

        // Staff na Manager wanaona tasks zao tu (assigned_to = wao)
        if ($isStaff || $isManager) {
            $employeeId = $user->employee_id;
            if ($employeeId) {
                $query->where('assigned_to', $employeeId);
            } else {
                // Kama hana employee record, haoni task yoyote
                $query->whereRaw('1 = 0');
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('search')) {
            $query->where('title', 'LIKE', '%' . $request->search . '%');
        }

        $tasks = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $stats = [
            'total' => Task::count(),
            'active' => Task::whereIn('status', ['todo', 'in_progress', 'review'])->count(),
            'completed' => Task::where('status', 'done')->count(),
            'overdue' => Task::where('due_date', '<', now())->where('status', '!=', 'done')->count(),
        ];

        $projects = Project::orderBy('name')->get();

        return view('tasks.index', compact('tasks', 'stats', 'projects'));
    }

    public function create()
    {
        $projects = Project::orderBy('name')->get();
        $employees = Employee::where('employment_status', 'active')->orderBy('first_name')->get();
        $regions = \App\Models\Region::orderBy('name')->get();
        return view('tasks.create', compact('projects', 'employees', 'regions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
            'assigned_to' => 'nullable|exists:employees,id',
            'status' => 'required|in:todo,in_progress,review,done',
            'priority' => 'required|in:low,medium,high,critical',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'estimated_hours' => 'nullable|integer|min:0',
            'progress' => 'nullable|integer|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        Task::create($validated);
        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        $task->load(['project', 'assignee']);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $projects = Project::orderBy('name')->get();
        $employees = Employee::where('employment_status', 'active')->orderBy('first_name')->get();
        $regions = \App\Models\Region::orderBy('name')->get();
        $task->load(['district', 'ward', 'region']);
        return view('tasks.edit', compact('task', 'projects', 'employees', 'regions'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
            'assigned_to' => 'nullable|exists:employees,id',
            'status' => 'required|in:todo,in_progress,review,done',
            'priority' => 'required|in:low,medium,high,critical',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'estimated_hours' => 'nullable|integer|min:0',
            'progress' => 'nullable|integer|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        $task->update($validated);
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate(['status' => 'required|in:todo,in_progress,review,done']);

        $data = ['status' => $request->status];
        if ($request->status === 'done') {
            $data['completed_date'] = now();
            $data['progress'] = 100;
        }

        $task->update($data);
        return redirect()->route('tasks.index')->with('success', 'Task status updated.');
    }
}


