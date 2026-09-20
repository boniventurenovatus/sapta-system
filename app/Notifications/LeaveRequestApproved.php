<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LeaveRequestApproved extends Notification
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
            'title' => 'Leave Request Approved',
            'message' => 'Your leave request has been approved.',
            'url' => '/leave-requests/' . $this->leaveRequest->id,
            'icon' => 'check-circle',
            'type' => 'success',
        ];
    }
}
