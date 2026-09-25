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
        $query = Employee::with(['organization', 'department', 'position']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_number', 'like', "%{$search}%");
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

        $employees = $query->orderBy('created_at', 'desc')->paginate(15);

        // Stats
        $stats = [
            'total' => Employee::count(),
            'active' => Employee::where('employment_status', 'active')->count(),
            'inactive' => Employee::where('employment_status', 'inactive')->count(),
            'on_leave' => Employee::where('employment_status', 'on_leave')->count(),
            'suspended' => Employee::where('employment_status', 'suspended')->count(),
            'terminated' => Employee::where('employment_status', 'terminated')->count(),
        ];

        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('employees.index', compact('employees', 'stats', 'departments'));
    }public function create(): View
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

        $validated['employee_number'] = strtoupper(trim($validated['employee_number']));

        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $request->file('profile_image')->store('employees', 'public');
        }

        // Unda Employee
        $employee = Employee::create($validated);

        // Auto-generate credentials + Tuma Internal Message + Email
        if ($request->filled('role_id')) {
            $result = \App\Services\NotificationService::createForEmployee(
                $employee,
                $request->role_id
            );

            session()->flash('generated_credentials', [
                'username' => $result['username'],
                'email' => $employee->email,
                'password' => $result['password'],
                'role' => \App\Models\Role::find($request->role_id)?->name,
                'expires_at' => now()->addDays(7)->format('d M Y'),
                'internal_sent' => $result['internal_sent'],
                'email_sent' => $result['email_sent'],
            ]);
        }

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee created successfully. Credentials sent via Internal Message & Email.');
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

        $this->ensureAuditTableExists(); EmployeeAuditLog::create([
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

        $this->ensureAuditTableExists(); EmployeeAuditLog::create([
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

        $this->ensureAuditTableExists(); EmployeeAuditLog::create([
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

        $this->ensureAuditTableExists(); EmployeeAuditLog::create([
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

    /**
     * Onyesha credentials za Employee — kwa Admin
     */
    public function credentials(Employee $employee): View
    {
        $user = $employee->user;

        if (!$user) {
            return view('employees.credentials', [
                'employee' => $employee,
                'user' => null,
                'roleName' => null,
                'message' => null,
            ]);
        }

        // Role name
        $roleName = $user->roles()->first()?->name ?? 'No Role';

        // ============================================================
        // USIONYESHE PASSWORD — onyesha tu metadata
        // ============================================================
        $credentialInfo = [
            'email' => $user->email,
            'username' => $user->username,
            'role' => $roleName,
            'is_first_login' => $user->is_first_login,
            'first_password_expires_at' => $user->first_password_expires_at,
            'credentials_expires_at' => $user->credentials_expires_at,
            'last_login_at' => $user->last_login_at,
            'account_status' => $user->account_status,
        ];

        return view('employees.credentials', [
            'employee' => $employee,
            'user' => $user,
            'roleName' => $roleName,
            'credentialInfo' => $credentialInfo,
            'message' => null,
        ]);
    }public function resetPassword(Employee $employee): RedirectResponse
    {
        $user = $employee->user;

        if (!$user) {
            return back()->with('error', 'Employee does not have a User Account.');
        }

        // ============================================================
        // RANDOM PASSWORD — cryptographically secure
        // ============================================================
        $newPassword = \Illuminate\Support\Str::password(16);

        $expiryDays = (int) config('sapta.credentials_expiry_days', 7);

        // ============================================================
        // forceFill — kwa sababu fields ziko kwenye $guarded
        // ============================================================
        $user->forceFill([
            'password_hash' => \Hash::make($newPassword),
            'is_first_login' => true,
            'first_password_expires_at' => now()->addDays($expiryDays),
            'credentials_expires_at' => now()->addDays($expiryDays),
            'password_changed_at' => null,
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ])->save();

        // ============================================================
        // Tuma credentials kwa email — sio kwenye response
        // ============================================================
        try {
            \App\Services\NotificationService::sendCredentials($user, $newPassword);
        } catch (\Exception $e) {
            \Log::error('Failed to send credentials: ' . $e->getMessage());
        }

        // ============================================================
        // Audit log
        // ============================================================
        UserActivityLog::log(
            action: 'password_reset',
            module: 'employee',
            description: 'Password reset for: ' . $employee->full_name,
            subjectId: $employee->id,
            subjectType: 'App\Models\Employee'
        );

        // ============================================================
        // USIONYESHE PASSWORD KWENYE RESPONSE
        // ============================================================
        return back()->with('success', 'Password reset successfully. Credentials zimetumwa kwa ' . $user->email . '.');
    }
    /**
     * Unda employee_audit_logs table kama haipo
     * (defensive — inazuia error kwenye production)
     */
    private function ensureAuditTableExists()
    {
        if (!\Schema::hasTable('employee_audit_logs')) {
            try {
                \Schema::create('employee_audit_logs', function ($table) {
                    $table->id();
                    $table->unsignedBigInteger('employee_id')->nullable();
                    $table->unsignedBigInteger('user_id')->nullable();
                    $table->string('action');
                    $table->string('old_status')->nullable();
                    $table->string('new_status')->nullable();
                    $table->text('reason')->nullable();
                    $table->string('ip_address')->nullable();
                    $table->text('user_agent')->nullable();
                    $table->timestamps();
                });
            } catch (\Exception $e) {
                \Log::error('Failed to create employee_audit_logs: ' . $e->getMessage());
            }
        }
    }
}