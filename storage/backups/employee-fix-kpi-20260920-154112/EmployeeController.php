<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $search = trim($request->input('search', ''));
        $departmentId = $request->input('department', '');
        $status = $request->input('status', '');

        $employees = Employee::query()
            ->with([
                'organization',
                'department',
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('employee_number', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('job_title', 'like', "%{$search}%");
                });
            })
            ->when($departmentId !== '', function ($query) use ($departmentId) {
                $query->where('department_id', $departmentId);
            })
            ->when($status !== '', function ($query) use ($status) {
                $query->where('employment_status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $departments = Department::query()
            ->orderBy('name')
            ->get();

        $totalEmployees = Employee::count();

        $activeEmployees = Employee::where(
            'employment_status',
            'active'
        )->count();

        $inactiveEmployees = Employee::where(
            'employment_status',
            'inactive'
        )->count();

        $onLeaveEmployees = Employee::where(
            'employment_status',
            'on_leave'
        )->count();

        return view(
            'employees.index',
            compact(
                'employees',
                'departments',
                'search',
                'departmentId',
                'status',
                'totalEmployees',
                'activeEmployees',
                'inactiveEmployees',
                'onLeaveEmployees'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(): View
    {
        $organizations = Organization::query()
            ->orderBy('name')
            ->get();

        $departments = Department::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $regions = \App\Models\Region::orderBy('name')->get();

        return view(
            'employees.create',
            compact(
                'organizations',
                'departments',
                'regions'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $validated['employee_number'] = strtoupper(
            trim($validated['employee_number'])
        );

        if (
            !empty($validated['organization_id']) &&
            !empty($validated['department_id'])
        ) {
            $belongsToOrganization = Department::query()
                ->where('id', $validated['department_id'])
                ->where(
                    'organization_id',
                    $validated['organization_id']
                )
                ->exists();

            if (!$belongsToOrganization) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'department_id' =>
                            'The selected department does not belong to the selected organization.',
                    ]);
            }
        }

        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] =
                $request->file('profile_image')
                    ->store('employees', 'public');
        }

        Employee::create($validated);

        return redirect()
            ->route('employees.index')
            ->with(
                'success',
                'Employee created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(Employee $employee): View
    {
        $employee->load([
            'organization',
            'department',
            'organizationalUnit',
            'user',
            'positions',
            'attendances',
        ]);

        return view(
            'employees.show',
            compact('employee')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Employee $employee): View
    {
        $organizations = Organization::query()
            ->orderBy('name')
            ->get();

        $departments = Department::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $regions = \App\Models\Region::orderBy('name')->get();
        $employee->load(['district', 'ward', 'region']);

        return view(
            'employees.edit',
            compact(
                'employee',
                'organizations',
                'departments',
                'regions'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse {

        $validated = $request->validated();

        $validated['employee_number'] = strtoupper(
            trim($validated['employee_number'])
        );

        if (
            !empty($validated['organization_id']) &&
            !empty($validated['department_id'])
        ) {
            $belongsToOrganization = Department::query()
                ->where('id', $validated['department_id'])
                ->where(
                    'organization_id',
                    $validated['organization_id']
                )
                ->exists();

            if (!$belongsToOrganization) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'department_id' =>
                            'The selected department does not belong to the selected organization.',
                    ]);
            }
        }

        if ($request->hasFile('profile_image')) {

            if (
                !empty($employee->profile_image) &&
                Storage::disk('public')->exists(
                    $employee->profile_image
                )
            ) {
                Storage::disk('public')->delete(
                    $employee->profile_image
                );
            }

            $validated['profile_image'] =
                $request->file('profile_image')
                    ->store('employees', 'public');
        }

        $employee->update($validated);

        return redirect()
            ->route(
                'employees.show',
                $employee
            )
            ->with(
                'success',
                'Employee updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Employee $employee
    ): RedirectResponse {

        try {

            /*
            |--------------------------------------------------------------------------
            | DELETE PROFILE IMAGE
            |--------------------------------------------------------------------------
            */

            if (
                !empty($employee->profile_image) &&
                Storage::disk('public')->exists(
                    $employee->profile_image
                )
            ) {
                Storage::disk('public')->delete(
                    $employee->profile_image
                );
            }


            /*
            |--------------------------------------------------------------------------
            | DELETE EMPLOYEE
            |--------------------------------------------------------------------------
            */

            $employee->delete();


            /*
            |--------------------------------------------------------------------------
            | SUCCESS
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('employees.index')
                ->with(
                    'success',
                    'Employee deleted successfully.'
                );

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | DELETE FAILED
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('employees.index')
                ->with(
                    'error',
                    'Employee cannot be deleted because this employee is linked to other records.'
                );
        }
    }
}




