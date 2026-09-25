<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
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
        return auth()->check();
    }

    public function view(User $user, Project $project): bool
    {
        return auth()->check();
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole([
            'director', 'admin_director', 'program_director',
            'project_manager', 'manager', 'ceo',
        ]);
    }

    public function update(User $user, Project $project): bool
    {
        return $user->hasAnyRole([
            'director', 'admin_director', 'program_director',
            'project_manager', 'manager', 'ceo',
        ]);
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->hasAnyRole(['director', 'program_director']);
    }
}