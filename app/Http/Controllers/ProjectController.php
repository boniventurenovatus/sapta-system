<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Organization;
use App\Models\Employee;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Project::class);

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
        $this->authorize('create', Project::class);

        $organizations = Organization::all();
        $employees = Employee::where('employment_status', 'active')->get();
        $regions = \App\Models\Region::orderBy('name')->get();
        return view('projects.create', compact('organizations', 'employees', 'regions'));
    }

    public function store(StoreProjectRequest $request)
    {
        Project::create($request->validated());
        return redirect()->route('projects.index')->with('success', 'Project created successfully');
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        $organizations = Organization::all();
        $employees = Employee::where('employment_status', 'active')->get();
        $regions = \App\Models\Region::orderBy('name')->get();
        $project->load(['district', 'ward', 'region']);
        return view('projects.edit', compact('project', 'organizations', 'employees', 'regions'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->validated());
        return redirect()->route('projects.index')->with('success', 'Project updated successfully');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully');
    }

    public function updateProgress(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $request->validate(['progress' => 'required|integer|min:0|max:100']);
        $project->update(['progress' => $request->progress]);
        return redirect()->route('projects.show', $project)->with('success', 'Progress updated successfully');
    }
}