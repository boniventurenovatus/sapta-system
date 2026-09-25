<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;

class EmployeePolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            'director', 'admin_director', 'program_director',
            'hr_manager', 'hr_officer', 'ceo', 'bod', 'manager',
        ]);
    }

    public function view(User $user, Employee $employee): bool
    {
        if ($user->hasAnyRole(['director', 'admin_director', 'program_director', 'hr_manager', 'hr_officer', 'ceo', 'bod', 'manager'])) {
            return true;
        }
        if ($user->employee && $user->employee->id === $employee->id) {
            return true;
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole([
            'director', 'admin_director', 'program_director',
            'hr_manager', 'hr_officer',
        ]);
    }

    public function update(User $user, Employee $employee): bool
    {
        return $user->hasAnyRole([
            'director', 'admin_director', 'program_director',
            'hr_manager', 'hr_officer',
        ]);
    }

    public function delete(User $user, Employee $employee): bool
    {
        return $user->hasAnyRole(['director', 'hr_manager']);
    }

    public function deactivate(User $user, Employee $employee): bool
    {
        return $user->hasAnyRole([
            'director', 'admin_director', 'hr_manager', 'hr_officer', 'ceo',
        ]);
    }

    public function terminate(User $user, Employee $employee): bool
    {
        return $user->hasAnyRole(['director', 'hr_manager', 'ceo']);
    }

    public function activate(User $user, Employee $employee): bool
    {
        return $user->hasAnyRole([
            'director', 'admin_director', 'hr_manager', 'hr_officer', 'ceo',
        ]);
    }

    public function suspend(User $user, Employee $employee): bool
    {
        return $user->hasAnyRole([
            'director', 'admin_director', 'hr_manager', 'hr_officer', 'ceo',
        ]);
    }

    public function viewCredentials(User $user, Employee $employee): bool
    {
        return $user->hasAnyRole([
            'director', 'admin_director', 'hr_manager', 'hr_officer',
        ]);
    }

    public function resetPassword(User $user, Employee $employee): bool
    {
        return $user->hasAnyRole(['hr_manager', 'hr_officer']);
    }
}