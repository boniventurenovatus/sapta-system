<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Employee;
use App\Models\Project;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Payslip;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Policies\EmployeePolicy;
use App\Policies\ProjectPolicy;
use App\Policies\UserPolicy;
use App\Policies\RolePolicy;
use App\Policies\PermissionPolicy;
use App\Policies\PayslipPolicy;
use App\Policies\AttendancePolicy;
use App\Policies\LeaveRequestPolicy;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
        }

        // ============================================================
        // POLICIES
        // ============================================================
        Gate::policy(Employee::class, EmployeePolicy::class);
        Gate::policy(Project::class, ProjectPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Permission::class, PermissionPolicy::class);
        Gate::policy(Payslip::class, PayslipPolicy::class);
        Gate::policy(Attendance::class, AttendancePolicy::class);
        Gate::policy(LeaveRequest::class, LeaveRequestPolicy::class);
    }
}