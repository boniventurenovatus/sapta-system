<?php

namespace App\Mail;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmployeeStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Employee $employee,
        public string $action,
        public string $oldStatus,
        public string $newStatus,
        public ?string $reason = null,
        public ?string $performedBy = null,
    ) {}

    public function envelope(): Envelope
    {
        $subject = match ($this->action) {
            'deactivate' => 'Employee Deactivated',
            'terminate'  => 'Employee Terminated',
            'activate'   => 'Employee Activated',
            'suspend'    => 'Employee Suspended',
            default      => 'Employee Status Changed',
        };

        return new Envelope(
            subject: $subject . ' — ' . $this->employee->full_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.employee-status-changed',
        );
    }
}