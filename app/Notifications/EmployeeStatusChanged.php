<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EmployeeStatusChanged extends Notification
{
    use Queueable;

    protected $employee;
    protected $action;
    protected $oldStatus;
    protected $newStatus;
    protected $reason;
    protected $performedBy;

    public function __construct($employee, $action, $oldStatus, $newStatus, $reason = null, $performedBy = null)
    {
        $this->employee = $employee;
        $this->action = $action;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->reason = $reason;
        $this->performedBy = $performedBy;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $icon = match ($this->action) {
            'activate' => 'user-check',
            'deactivate' => 'user-slash',
            'suspend' => 'user-clock',
            'terminate' => 'user-times',
            default => 'user',
        };

        $type = match ($this->action) {
            'activate' => 'success',
            'deactivate' => 'warning',
            'suspend' => 'warning',
            'terminate' => 'danger',
            default => 'info',
        };

        return [
            'title' => 'Employee ' . ucfirst($this->action),
            'message' => $this->employee->full_name . ' has been ' . $this->action . 'd.',
            'url' => '/employees/' . $this->employee->id,
            'icon' => $icon,
            'type' => $type,
            'reason' => $this->reason,
            'performed_by' => $this->performedBy,
        ];
    }
}