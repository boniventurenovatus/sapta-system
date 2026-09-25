<?php

namespace App\Policies;

use App\Models\Payslip;
use App\Models\User;

class PayslipPolicy
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
        return $user->hasAnyRole(['finance_manager', 'accountant', 'hr_manager', 'hr_officer']);
    }

    public function view(User $user, Payslip $payslip): bool
    {
        // Employee anaweza kuona payslip yake pekee
        if ($user->employee_id && $payslip->employee_id === $user->employee_id) {
            return true;
        }
        return $user->hasAnyRole(['finance_manager', 'accountant', 'hr_manager', 'hr_officer']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['finance_manager', 'hr_manager']);
    }

    public function update(User $user, Payslip $payslip): bool
    {
        return $user->hasAnyRole(['finance_manager', 'hr_manager']);
    }

    public function delete(User $user, Payslip $payslip): bool
    {
        return $user->hasRole('finance_manager');
    }

    public function approve(User $user, Payslip $payslip): bool
    {
        return $user->hasAnyRole(['finance_manager', 'director']);
    }

    public function markAsPaid(User $user, Payslip $payslip): bool
    {
        return $user->hasAnyRole(['finance_manager', 'accountant']);
    }
}