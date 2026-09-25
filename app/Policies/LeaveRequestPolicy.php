<?php

namespace App\Policies;

use App\Models\LeaveRequest;
use App\Models\User;

class LeaveRequestPolicy
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
        return true;
    }

    public function view(User $user, LeaveRequest $leaveRequest): bool
    {
        // Employee anaweza kuona leave request yake pekee
        if ($user->employee_id && $leaveRequest->employee_id === $user->employee_id) {
            return true;
        }
        return $user->hasAnyRole(['hr_manager', 'hr_officer', 'manager', 'director']);
    }

    public function create(User $user): bool
    {
        return true; // Kila mtu anaweza kuomba leave
    }

    public function update(User $user, LeaveRequest $leaveRequest): bool
    {
        // Employee anaweza ku-update leave request yake pekee (kama bado pending)
        if ($user->employee_id && $leaveRequest->employee_id === $user->employee_id && $leaveRequest->status === 'pending') {
            return true;
        }
        return $user->hasAnyRole(['hr_manager', 'hr_officer']);
    }

    public function delete(User $user, LeaveRequest $leaveRequest): bool
    {
        if ($user->employee_id && $leaveRequest->employee_id === $user->employee_id && $leaveRequest->status === 'pending') {
            return true;
        }
        return $user->hasAnyRole(['hr_manager', 'hr_officer']);
    }

    public function approve(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->hasAnyRole(['hr_manager', 'hr_officer', 'manager', 'director']);
    }

    public function reject(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->hasAnyRole(['hr_manager', 'hr_officer', 'manager', 'director']);
    }
}