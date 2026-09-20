<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LeaveRequestSubmitted extends Notification
{
    use Queueable;

    protected $leaveRequest;

    public function __construct($leaveRequest)
    {
        $this->leaveRequest = $leaveRequest;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Leave Request',
            'message' => 'Leave request from ' . ($this->leaveRequest->employee->first_name ?? 'Employee'),
            'url' => '/leave-requests/' . $this->leaveRequest->id,
            'icon' => 'calendar-check',
            'type' => 'info',
        ];
    }
}
