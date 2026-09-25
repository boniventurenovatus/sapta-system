<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;

class AttendancePolicy
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
        return true; // Kila mtu anaweza kuona attendance list (scoped)
    }

    public function view(User $user, Attendance $attendance): bool
    {
        // Employee anaweza kuona attendance yake pekee
        if ($user->employee_id && $attendance->employee_id === $user->employee_id) {
            return true;
        }
        return $user->hasAnyRole(['hr_manager', 'hr_officer', 'manager', 'director']);
    }

    public function create(User $user): bool
    {
        return true; // Kila mtu anaweza ku-record attendance yake
    }

    public function update(User $user, Attendance $attendance): bool
    {
        // Employee anaweza ku-update attendance yake pekee (kama bado haijafungwa)
        if ($user->employee_id && $attendance->employee_id === $user->employee_id) {
            return true;
        }
        return $user->hasAnyRole(['hr_manager', 'hr_officer']);
    }

    public function delete(User $user, Attendance $attendance): bool
    {
        return $user->hasAnyRole(['hr_manager', 'hr_officer']);
    }
}