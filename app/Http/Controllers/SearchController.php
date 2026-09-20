<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Task;
use App\Models\Document;
use App\Models\Department;
use App\Models\Position;
use App\Models\Training;
use App\Models\JobPosting;
use App\Models\User;

class SearchController extends Controller
{
    /**
     * Search page â€” inaonyesha results zote.
     */
    public function index(Request $request)
    {
        $query = trim($request->get('q', ''));
        $module = $request->get('module', 'all');
        $regionId = $request->get('region_id');

        $results = [
            'employees' => collect(),
            'projects' => collect(),
            'tasks' => collect(),
            'documents' => collect(),
            'organizations' => collect(),
            'departments' => collect(),
            'positions' => collect(),
            'trainings' => collect(),
            'jobs' => collect(),
            'users' => collect(),
        ];

        $totalResults = 0;

        if (strlen($query) >= 2) {
            // EMPLOYEES
            if ($module === 'all' || $module === 'employees') {
                $q = Employee::with(['region', 'district', 'ward', 'department'])
                    ->where(function($q) use ($query) {
                        $q->where('first_name', 'LIKE', "%{$query}%")
                          ->orWhere('last_name', 'LIKE', "%{$query}%")
                          ->orWhere('email', 'LIKE', "%{$query}%")
                          ->orWhere('employee_number', 'LIKE', "%{$query}%")
                          ->orWhere('job_title', 'LIKE', "%{$query}%");
                    });
                if ($regionId) $q->where('region_id', $regionId);
                $results['employees'] = $q->limit(20)->get();
                $totalResults += $results['employees']->count();
            }

            // PROJECTS
            if ($module === 'all' || $module === 'projects') {
                $q = Project::with(['region', 'district', 'ward'])
                    ->where(function($q) use ($query) {
                        $q->where('name', 'LIKE', "%{$query}%")
                          ->orWhere('code', 'LIKE', "%{$query}%")
                          ->orWhere('description', 'LIKE', "%{$query}%");
                    });
                if ($regionId) $q->where('region_id', $regionId);
                $results['projects'] = $q->limit(20)->get();
                $totalResults += $results['projects']->count();
            }

            // TASKS
            if ($module === 'all' || $module === 'tasks') {
                $q = Task::with(['project', 'region', 'district'])
                    ->where(function($q) use ($query) {
                        $q->where('title', 'LIKE', "%{$query}%")
                          ->orWhere('description', 'LIKE', "%{$query}%");
                    });
                $results['tasks'] = $q->limit(20)->get();
                $totalResults += $results['tasks']->count();
            }

            // DOCUMENTS
            if ($module === 'all' || $module === 'documents') {
                $q = Document::with(['region', 'district'])
                    ->where(function($q) use ($query) {
                        $q->where('title', 'LIKE', "%{$query}%")
                          ->orWhere('document_number', 'LIKE', "%{$query}%")
                          ->orWhere('description', 'LIKE', "%{$query}%");
                    });
                $results['documents'] = $q->limit(20)->get();
                $totalResults += $results['documents']->count();
            }

            // ORGANIZATIONS
            if ($module === 'all' || $module === 'organizations') {
                $q = Organization::where(function($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('code', 'LIKE', "%{$query}%");
                });
                $results['organizations'] = $q->limit(20)->get();
                $totalResults += $results['organizations']->count();
            }

            // DEPARTMENTS
            if ($module === 'all' || $module === 'departments') {
                $q = Department::where(function($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('code', 'LIKE', "%{$query}%");
                });
                $results['departments'] = $q->limit(20)->get();
                $totalResults += $results['departments']->count();
            }

            // POSITIONS
            if ($module === 'all' || $module === 'positions') {
                $q = Position::where(function($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('code', 'LIKE', "%{$query}%");
                });
                $results['positions'] = $q->limit(20)->get();
                $totalResults += $results['positions']->count();
            }

            // TRAININGS
            if ($module === 'all' || $module === 'trainings') {
                $q = Training::with(['region', 'district'])
                    ->where(function($q) use ($query) {
                        $q->where('title', 'LIKE', "%{$query}%")
                          ->orWhere('description', 'LIKE', "%{$query}%");
                    });
                $results['trainings'] = $q->limit(20)->get();
                $totalResults += $results['trainings']->count();
            }

            // JOBS
            if ($module === 'all' || $module === 'jobs') {
                $q = JobPosting::with(['region', 'district'])
                    ->where(function($q) use ($query) {
                        $q->where('title', 'LIKE', "%{$query}%")
                          ->orWhere('description', 'LIKE', "%{$query}%");
                    });
                $results['jobs'] = $q->limit(20)->get();
                $totalResults += $results['jobs']->count();
            }

            // USERS
            if ($module === 'all' || $module === 'users') {
                $q = User::where(function($q) use ($query) {
                    $q->where('username', 'LIKE', "%{$query}%")
                      ->orWhere('email', 'LIKE', "%{$query}%");
                });
                $results['users'] = $q->limit(20)->get();
                $totalResults += $results['users']->count();
            }
        }

        $regions = \App\Models\Region::orderBy('name')->get();

        return view('search.index', compact('query', 'module', 'regionId', 'results', 'totalResults', 'regions'));
    }

    /**
     * Live search â€” inarudisha JSON kwa dropdown.
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $results = [];

        // EMPLOYEES
        try {
            foreach (Employee::where('first_name', 'LIKE', "%{$query}%")
                ->orWhere('last_name', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%")
                ->orWhere('employee_number', 'LIKE', "%{$query}%")
                ->limit(5)->get() as $emp) {
                $results[] = [
                    'title' => $emp->first_name . ' ' . $emp->last_name,
                    'type' => 'Employee',
                    'description' => $emp->employee_number ?? $emp->email ?? 'Staff',
                    'url' => '/employees/' . $emp->id,
                    'icon' => 'fa-user',
                ];
            }
        } catch (\Exception $e) {}

        // PROJECTS
        try {
            foreach (Project::where('name', 'LIKE', "%{$query}%")
                ->orWhere('code', 'LIKE', "%{$query}%")
                ->limit(5)->get() as $p) {
                $results[] = [
                    'title' => $p->name,
                    'type' => 'Project',
                    'description' => $p->code ?? 'Project',
                    'url' => '/projects/' . $p->id,
                    'icon' => 'fa-folder-open',
                ];
            }
        } catch (\Exception $e) {}

        // TASKS
        try {
            foreach (Task::where('title', 'LIKE', "%{$query}%")->limit(5)->get() as $t) {
                $results[] = [
                    'title' => $t->title,
                    'type' => 'Task',
                    'description' => $t->project?->name ?? 'Task',
                    'url' => '/tasks/' . $t->id,
                    'icon' => 'fa-list-check',
                ];
            }
        } catch (\Exception $e) {}

        // ORGANIZATIONS
        try {
            foreach (Organization::where('name', 'LIKE', "%{$query}%")
                ->orWhere('code', 'LIKE', "%{$query}%")
                ->limit(5)->get() as $org) {
                $results[] = [
                    'title' => $org->name,
                    'type' => 'Organization',
                    'description' => $org->code ?? 'Organization',
                    'url' => '/organizations/' . $org->id,
                    'icon' => 'fa-building',
                ];
            }
        } catch (\Exception $e) {}

        // USERS
        try {
            foreach (User::where('username', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%")
                ->limit(5)->get() as $user) {
                $results[] = [
                    'title' => $user->username,
                    'type' => 'User',
                    'description' => $user->email ?? 'System User',
                    'url' => '/users/' . $user->id,
                    'icon' => 'fa-user-shield',
                ];
            }
        } catch (\Exception $e) {}

        return response()->json($results);
    }
}
