<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeAuditLog;
use App\Models\UserActivityLog;
use App\Mail\EmployeeStatusChanged;
use App\Notifications\EmployeeStatusChanged as EmployeeStatusNotification;
use Illuminate\Support\Facades\Mail;
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
        $query = Employee::with(['department', 'position', 'organization']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Department filter
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('employment_status', $request->status);
        }

        $employees = $query->orderBy('first_name')->paginate(15)->withQueryString();

        // KPI cards
        $totalEmployees    = Employee::count();
        $activeEmployees   = Employee::where('employment_status', 'active')->count();
        $inactiveEmployees = Employee::where('employment_status', 'inactive')->count();
        $onLeaveEmployees  = Employee::where('employment_status', 'on_leave')->count();
        $suspendedEmployees  = Employee::where('employment_status', 'suspended')->count();
        $terminatedEmployees = Employee::where('employment_status', 'terminated')->count();

        // Dropdown data
        $departments = Department::orderBy('name')->get(['id', 'name']);

        return view('employees.index', compact(
            'employees',
            'totalEmployees',
            'activeEmployees',
            'inactiveEmployees',
            'onLeaveEmployees',
            'suspendedEmployees',
            'terminatedEmployees',
            'departments'
        ));
    }

    /**
     * Show create form.
     */
    public function create(): View
    {
        // ===== LOCATIONS =====
        $regions = \App\Models\Region::orderBy('name')->get();
        $districts = \App\Models\District::orderBy('name')->get();
        $wards = \App\Models\Ward::orderBy('name')->get();

        // ===== ORGANIZATION =====
        $departments = Department::where('is_active', true)
            ->orderBy('name')
            ->get();

        $organizations = \App\Models\Organization::orderBy('name')->get();

        $organizationalUnits = \App\Models\OrganizationalUnit::orderBy('name')->get();

        $positions = \App\Models\Position::orderBy('title')->get();

        // ===== ROLES =====
        $roles = \App\Models\Role::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('employees.create', compact(
            'regions',
            'districts',
            'wards',
            'departments',
            'organizations',
            'organizationalUnits',
            'positions',
            'roles'
        ));
    }public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // ============================================================
        // 1. EMPLOYEE NUMBER — uppercase
        // ============================================================
        $validated['employee_number'] = strtoupper(trim($validated['employee_number']));

        // ============================================================
        // 2. PROFILE IMAGE
        // ============================================================
        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $request->file('profile_image')->store('employees', 'public');
        }

        // ============================================================
        // 3. UNDA EMPLOYEE
        // ============================================================
        $employee = Employee::create($validated);

        // ============================================================
        // 4. UNDA USER ACCOUNT (kama imechaguliwa)
        // ============================================================
        if ($request->boolean('create_user_account') && $request->filled('username') && $request->filled('user_password')) {
            $user = \App\Models\User::create([
                'employee_id' => $employee->id,
                'username' => $request->username,
                'email' => $employee->email,
                'password_hash' => \Hash::make($request->user_password),
                'account_status' => 'active',
                'is_first_login' => true,
            ]);

            if ($request->filled('role_id')) {
                $user->roles()->attach($request->role_id);
            }
        }

        // ============================================================
        // 5. REDIRECT
        // ============================================================
        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }public function show(Employee $employee): View
    {
        $employee->load([
            'organization',
            'department',
            'organizationalUnit',
            'user',
            'positions',
            'attendances',
            'auditLogs',
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
        $districts = \App\Models\District::orderBy('name')->get();
        $wards = \App\Models\Ward::orderBy('name')->get();
        $employee->load(['district', 'ward', 'region']);

        return view(
            'employees.edit',
            compact(
                'employee',
                'organizations',
                'departments',
                'regions',
                'districts',
                'wards'
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

    /**
     * Deactivate employee — sets to inactive + disables login.
     */
    public function deactivate(Employee $employee): RedirectResponse
    {
        $oldStatus = $employee->employment_status;

        $employee->update(['employment_status' => 'inactive']);

        if ($employee->user) {
            $employee->user->update(['account_status' => 'inactive']);
        }

        EmployeeAuditLog::create([
            'employee_id' => $employee->id,
            'user_id'     => auth()->id(),
            'action'      => 'deactivate',
            'old_status'  => $oldStatus,
            'new_status'  => 'inactive',
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);

        $this->notifyHr($employee, 'deactivate', $oldStatus, 'inactive');
        $this->notifyHrNotification($employee, 'deactivate', $oldStatus, 'inactive');

        UserActivityLog::log(
            action: 'deactivate',
            module: 'employee',
            description: 'Deactivated: ' . $employee->full_name,
            subjectId: $employee->id,
            subjectType: 'App\Models\Employee'
        );

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee deactivated successfully.');
    }

    /**
     * Terminate employee — sets to terminated + disables login.
     */
    public function terminate(Employee $employee): RedirectResponse
    {
        $oldStatus = $employee->employment_status;

        $employee->update(['employment_status' => 'terminated']);

        if ($employee->user) {
            $employee->user->update(['account_status' => 'inactive']);
        }

        EmployeeAuditLog::create([
            'employee_id' => $employee->id,
            'user_id'     => auth()->id(),
            'action'      => 'terminate',
            'old_status'  => $oldStatus,
            'new_status'  => 'terminated',
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);

        $this->notifyHr($employee, 'terminate', $oldStatus, 'terminated');
        $this->notifyHrNotification($employee, 'terminate', $oldStatus, 'terminated');

        UserActivityLog::log(
            action: 'terminate',
            module: 'employee',
            description: 'Terminated: ' . $employee->full_name,
            subjectId: $employee->id,
            subjectType: 'App\Models\Employee'
        );

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee terminated successfully.');
    }

    /**
     * Activate employee — sets to active + enables login.
     */
    public function activate(Employee $employee): RedirectResponse
    {
        $oldStatus = $employee->employment_status;

        $employee->update(['employment_status' => 'active']);

        if ($employee->user) {
            $employee->user->update(['account_status' => 'active']);
        }

        EmployeeAuditLog::create([
            'employee_id' => $employee->id,
            'user_id'     => auth()->id(),
            'action'      => 'activate',
            'old_status'  => $oldStatus,
            'new_status'  => 'active',
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);

        $this->notifyHr($employee, 'activate', $oldStatus, 'active');
        $this->notifyHrNotification($employee, 'activate', $oldStatus, 'active');

        UserActivityLog::log(
            action: 'activate',
            module: 'employee',
            description: 'Activated: ' . $employee->full_name,
            subjectId: $employee->id,
            subjectType: 'App\Models\Employee'
        );

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee activated successfully.');
    }

    /**
     * Suspend employee — sets to suspended + disables login.
     */
    public function suspend(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'suspension_reason' => 'required|string|max:1000',
        ]);

        $oldStatus = $employee->employment_status;

        $employee->update([
            'employment_status'  => 'suspended',
            'suspension_reason'  => $validated['suspension_reason'],
            'suspended_at'       => now(),
            'suspended_by'       => auth()->id(),
        ]);

        if ($employee->user) {
            $employee->user->update(['account_status' => 'inactive']);
        }

        EmployeeAuditLog::create([
            'employee_id' => $employee->id,
            'user_id'     => auth()->id(),
            'action'      => 'suspend',
            'old_status'  => $oldStatus,
            'new_status'  => 'suspended',
            'reason'      => $validated['suspension_reason'],
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);

        $this->notifyHr($employee, 'suspend', $oldStatus, 'suspended', $validated['suspension_reason']);
        $this->notifyHrNotification($employee, 'suspend', $oldStatus, 'suspended', $validated['suspension_reason']);

        UserActivityLog::log(
            action: 'suspend',
            module: 'employee',
            description: 'Suspended: ' . $employee->full_name,
            subjectId: $employee->id,
            subjectType: 'App\Models\Employee',
            properties: ['reason' => $validated['suspension_reason']]
        );

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee suspended successfully.');
    }


    /**
     * Tuma email kwa HR kuhusu mabadiliko ya status.
     */
    private function notifyHr(
        Employee $employee,
        string $action,
        string $oldStatus,
        string $newStatus,
        ?string $reason = null
    ): void {
        // Pata HR users
        // Tafuta HR users — kwa email halisi
        $hrUsers = \App\Models\User::where('account_status', 'active')
            ->where(function ($q) {
                // Kwanza: users wenye roles za HR
                $q->whereHas('roles', function ($r) {
                    $r->whereIn('name', ['hr_manager', 'hr_officer', 'super_admin', 'admin']);
                })
                // AU: users wenye email halisi (sio @sapta.local)
                ->orWhere(function ($e) {
                    $e->where('email', 'LIKE', '%@gmail.com')
                      ->orWhere('email', 'LIKE', '%@yahoo.com')
                      ->orWhere('email', 'LIKE', '%@outlook.com');
                });
            })
            ->get();

        foreach ($hrUsers as $hr) {
            try {
                Mail::to($hr->email)->send(new EmployeeStatusChanged(
                    employee: $employee,
                    action: $action,
                    oldStatus: $oldStatus,
                    newStatus: $newStatus,
                    reason: $reason,
                    performedBy: auth()->user()?->username ?? 'System',
                ));
            } catch (\Exception $e) {
                \Log::error('Email notification failed: ' . $e->getMessage());
            }
        }
    }


    /**
     * Tuma notification kwa HR kuhusu mabadiliko ya status.
     */
    private function notifyHrNotification(
        Employee $employee,
        string $action,
        string $oldStatus,
        string $newStatus,
        ?string $reason = null
    ): void {
        $hrUsers = \App\Models\User::where('account_status', 'active')
            ->where(function ($q) {
                $q->whereHas('roles', function ($r) {
                    $r->whereIn('name', ['hr_manager', 'hr_officer', 'super_admin', 'admin']);
                })
                ->orWhere(function ($e) {
                    $e->where('email', 'LIKE', '%@gmail.com')
                      ->orWhere('email', 'LIKE', '%@yahoo.com')
                      ->orWhere('email', 'LIKE', '%@outlook.com');
                });
            })
            ->get();

        foreach ($hrUsers as $hr) {
            try {
                $hr->notify(new EmployeeStatusNotification(
                    employee: $employee,
                    action: $action,
                    oldStatus: $oldStatus,
                    newStatus: $newStatus,
                    reason: $reason,
                    performedBy: auth()->user()?->username ?? 'System',
                ));
            } catch (\Exception $e) {
                \Log::error('Notification failed: ' . $e->getMessage());
            }
        }
    }
}




