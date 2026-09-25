<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class RolePolicy
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
        return $user->hasRole('admin');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Role $role): bool
    {
        // Hauruhusiwi kubadilisha super_admin role
        if ($role->code === 'super_admin') {
            return false;
        }
        return $user->hasRole('admin');
    }

    public function delete(User $user, Role $role): bool
    {
        // Hauruhusiwi kufuta super_admin au admin role
        if (in_array($role->code, ['super_admin', 'admin'])) {
            return false;
        }
        return $user->hasRole('admin');
    }
}