<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LeaveRequestReturned extends Notification
{
    use Queueable;

    protected $leaveRequest;
    protected $reason;

    public function __construct($leaveRequest, $reason = null)
    {
        $this->leaveRequest = $leaveRequest;
        $this->reason = $reason;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Leave Request Returned',
            'message' => 'Your leave request was returned: ' . ($this->reason ?? 'Please review.'),
            'url' => '/leave-requests/' . $this->leaveRequest->id,
            'icon' => 'undo',
            'type' => 'warning',
        ];
    }
}