<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Organization;
use App\Models\Employee;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with(['organization', 'projectManager'])->paginate(10);
        $totalProjects = Project::count();
        $activeProjects = Project::whereIn('status', ['planning', 'in_progress'])->count();
        $completedProjects = Project::where('status', 'completed')->count();
        $overdueProjects = Project::where('end_date', '<', now())->where('status', '!=', 'completed')->count();
        
        return view('projects.index', compact(
            'projects',
            'totalProjects',
            'activeProjects',
            'completedProjects',
            'overdueProjects'
        ));
    }

    public function create()
    {
        $organizations = Organization::all();
        $employees = Employee::where('employment_status', 'active')->get();
        $regions = \App\Models\Region::orderBy('name')->get();
        return view('projects.create', compact('organizations', 'employees', 'regions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:projects',
            'description' => 'nullable|string',
            'organization_id' => 'nullable|exists:organizations,id',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
            'project_manager_id' => 'nullable|exists:employees,id',
            'status' => 'required|in:planning,in_progress,on_hold,completed,cancelled',
            'priority' => 'required|in:low,medium,high,critical',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'budget' => 'nullable|numeric',
            'progress' => 'nullable|integer|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        Project::create($request->all());
        return redirect()->route('projects.index')->with('success', 'Project created successfully');
    }

    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $organizations = Organization::all();
        $employees = Employee::where('employment_status', 'active')->get();
        $regions = \App\Models\Region::orderBy('name')->get();
        $project->load(['district', 'ward', 'region']);
        return view('projects.edit', compact('project', 'organizations', 'employees', 'regions'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:projects,code,' . $project->id,
            'description' => 'nullable|string',
            'organization_id' => 'nullable|exists:organizations,id',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
            'project_manager_id' => 'nullable|exists:employees,id',
            'status' => 'required|in:planning,in_progress,on_hold,completed,cancelled',
            'priority' => 'required|in:low,medium,high,critical',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'budget' => 'nullable|numeric',
            'progress' => 'nullable|integer|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        $project->update($request->all());
        return redirect()->route('projects.index')->with('success', 'Project updated successfully');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully');
    }

    public function updateProgress(Request $request, Project $project)
    {
        $request->validate(['progress' => 'required|integer|min:0|max:100']);
        $project->update(['progress' => $request->progress]);
        return redirect()->route('projects.show', $project)->with('success', 'Progress updated successfully');
    }
}


