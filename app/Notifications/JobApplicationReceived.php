<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class JobApplicationReceived extends Notification
{
    use Queueable;

    protected $application;

    public function __construct($application)
    {
        $this->application = $application;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Job Application',
            'message' => $this->application->applicant_name . ' applied for ' . ($this->application->jobPosting->title ?? 'a position'),
            'url' => '/recruitment/' . ($this->application->job_posting_id ?? ''),
            'icon' => 'user-plus',
            'type' => 'info',
        ];
    }
}
