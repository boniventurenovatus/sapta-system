<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Employee;
use App\Notifications\LeaveRequestSubmitted;
use App\Notifications\LeaveRequestApproved;
use App\Notifications\PaymentVoucherSubmitted;
use App\Notifications\PaymentVoucherApproved;
use App\Notifications\TrainingEnrolled;
use App\Notifications\JobApplicationReceived;

class NotificationHelper
{
    public static function notifyHRManagers($notification): void
    {
        $hrManagers = User::whereHas('roles', function ($q) {
            $q->where('name', 'LIKE', '%HR%')->orWhere('name', 'LIKE', '%Manager%');
        })->get();

        foreach ($hrManagers as $manager) {
            $manager->notify($notification);
        }
    }

    public static function notifyCEOs($notification): void
    {
        $ceos = User::whereHas('roles', function ($q) {
            $q->where('name', 'LIKE', '%CEO%')
              ->orWhere('name', 'LIKE', '%Director%')
              ->orWhere('name', 'LIKE', '%Super Admin%');
        })->get();

        foreach ($ceos as $ceo) {
            $ceo->notify($notification);
        }
    }

    public static function notifyUser($userId, $notification): void
    {
        $user = User::find($userId);
        if ($user) {
            $user->notify($notification);
        }
    }

    public static function notifyEmployee($employeeId, $notification): void
    {
        $employee = Employee::find($employeeId);
        if ($employee && $employee->user_id) {
            self::notifyUser($employee->user_id, $notification);
        }
    }

    public static function notifyLeaveSubmitted($leaveRequest): void
    {
        self::notifyHRManagers(new LeaveRequestSubmitted($leaveRequest));
    }

    public static function notifyLeaveApproved($leaveRequest): void
    {
        self::notifyEmployee($leaveRequest->employee_id, new LeaveRequestApproved($leaveRequest));
    }

    public static function notifyVoucherSubmitted($voucher): void
    {
        self::notifyCEOs(new PaymentVoucherSubmitted($voucher));
    }

    public static function notifyVoucherApproved($voucher): void
    {
        if ($voucher->prepared_by) {
            self::notifyUser($voucher->prepared_by, new PaymentVoucherApproved($voucher));
        }
    }

    public static function notifyTrainingEnrollment($enrollment): void
    {
        self::notifyEmployee($enrollment->employee_id, new TrainingEnrolled($enrollment));
    }

    public static function notifyJobApplication($application): void
    {
        self::notifyHRManagers(new JobApplicationReceived($application));
    }
}
