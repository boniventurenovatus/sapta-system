<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'hr_manager', 'hr_officer', 'ict_manager']);
    }

    public function view(User $user, User $model): bool
    {
        if ($user->id === $model->id) {
            return true;
        }
        return $user->hasAnyRole(['admin', 'hr_manager', 'hr_officer', 'ict_manager']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'hr_manager']);
    }

    public function update(User $user, User $model): bool
    {
        if ($user->id === $model->id) {
            return true;
        }
        return $user->hasAnyRole(['admin', 'hr_manager']);
    }

    public function delete(User $user, User $model): bool
    {
        if ($user->id === $model->id) {
            return false;
        }
        return $user->hasRole('admin');
    }

    public function suspend(User $user, User $model): bool
    {
        if ($user->id === $model->id) {
            return false;
        }
        return $user->hasAnyRole(['admin', 'hr_manager']);
    }

    public function activate(User $user, User $model): bool
    {
        return $user->hasAnyRole(['admin', 'hr_manager']);
    }
}