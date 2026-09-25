<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Employee;
use App\Policies\EmployeePolicy;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Force HTTPS kwenye production
        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
        }

        // ============================================================
        // POLICIES
        // ============================================================
        Gate::policy(Employee::class, EmployeePolicy::class);
    }
}