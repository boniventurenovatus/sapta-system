<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments.
     */
    public function index(Request $request): View
    {
        $search = trim(
            $request->input('search', '')
        );

        $status = $request->input('status', '');

        $departments = Department::query()
            ->with('organization')
            ->withCount('employees')

            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas(
                            'organization',
                            function ($organizationQuery) use ($search) {
                                $organizationQuery
                                    ->where(
                                        'name',
                                        'like',
                                        "%{$search}%"
                                    )
                                    ->orWhere(
                                        'code',
                                        'like',
                                        "%{$search}%"
                                    );
                            }
                        );
                });
            })

            ->when(
                $status === 'active',
                function ($query) {
                    $query->where('is_active', true);
                }
            )

            ->when(
                $status === 'inactive',
                function ($query) {
                    $query->where('is_active', false);
                }
            )

            ->latest()

            ->paginate(10)

            ->withQueryString();

        return view(
            'departments.index',
            compact(
                'departments',
                'search',
                'status'
            )
        );
    }

    /**
     * Show the form for creating a new department.
     */
    public function create(): View
    {
        $regions = \App\Models\Region::orderBy('name')->get();
        $organizations = Organization::query()
            ->orderBy('name')
            ->get();

        return view(
            'departments.create',
            compact('organizations')
        );
    }

    /**
     * Store a newly created department.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'organization_id' => [
                'required',
                'integer',
                'exists:organizations,id',
            ],

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'code' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
            ],

            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],

            'region_id' => [
                'nullable',
                'integer',
                'exists:regions,id',
            ],

            'district_id' => [
                'nullable',
                'integer',
                'exists:districts,id',
            ],

            'ward_id' => [
                'nullable',
                'integer',
                'exists:wards,id',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Normalize department code
        |--------------------------------------------------------------------------
        */

        $validated['code'] = strtoupper(
            trim($validated['code'])
        );

        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate code within organization
        |--------------------------------------------------------------------------
        */

        $exists = Department::query()
            ->where(
                'organization_id',
                $validated['organization_id']
            )
            ->where(
                'code',
                $validated['code']
            )
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'code' =>
                        'This department code already exists in the selected organization.',
                ]);
        }

        Department::create($validated);

        return redirect()
            ->route('departments.index')
            ->with(
                'success',
                'Department created successfully.'
            );
    }

    /**
     * Display the specified department.
     */
    public function show(
        Department $department
    ): View {
        $department->load([
            'organization',
            'employees',
        ]);

        return view(
            'departments.show',
            compact('department')
        );
    }

    /**
     * Show the form for editing the specified department.
     */
    public function edit(
        Department $department
    ): View {
        $regions = \App\Models\Region::orderBy('name')->get();
        $department->load(['district', 'ward', 'region']);
        $organizations = Organization::query()
            ->orderBy('name')
            ->get();

        return view(
            'departments.edit',
            compact(
                'department',
                'organizations'
            )
        );
    }

    /**
     * Update the specified department.
     */
    public function update(
        Request $request,
        Department $department
    ): RedirectResponse {
        $validated = $request->validate([
            'organization_id' => [
                'required',
                'integer',
                'exists:organizations,id',
            ],

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'code' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
            ],

            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],

            'region_id' => [
                'nullable',
                'integer',
                'exists:regions,id',
            ],

            'district_id' => [
                'nullable',
                'integer',
                'exists:districts,id',
            ],

            'ward_id' => [
                'nullable',
                'integer',
                'exists:wards,id',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Normalize department code
        |--------------------------------------------------------------------------
        */

        $validated['code'] = strtoupper(
            trim($validated['code'])
        );

        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate code within organization
        |--------------------------------------------------------------------------
        */

        $exists = Department::query()
            ->where(
                'organization_id',
                $validated['organization_id']
            )
            ->where(
                'code',
                $validated['code']
            )
            ->where(
                'id',
                '!=',
                $department->id
            )
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'code' =>
                        'This department code already exists in the selected organization.',
                ]);
        }

        $department->update($validated);

        return redirect()
            ->route(
                'departments.show',
                $department
            )
            ->with(
                'success',
                'Department updated successfully.'
            );
    }

    /**
     * Remove the specified department.
     */
    public function destroy(
        Department $department
    ): RedirectResponse {
        /*
        |--------------------------------------------------------------------------
        | Prevent deleting department with employees
        |--------------------------------------------------------------------------
        */

        if ($department->employees()->exists()) {
            return redirect()
                ->route('departments.index')
                ->with(
                    'error',
                    'This department cannot be deleted because it has employees assigned to it.'
                );
        }

        $departmentName = $department->name;

        $department->delete();

        return redirect()
            ->route('departments.index')
            ->with(
                'success',
                "Department \"{$departmentName}\" deleted successfully."
            );
    }
}



