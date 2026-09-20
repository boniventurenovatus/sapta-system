<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TrainingEnrolled extends Notification
{
    use Queueable;

    protected $enrollment;

    public function __construct($enrollment)
    {
        $this->enrollment = $enrollment;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Enrolled in Training',
            'message' => 'You have been enrolled in: ' . ($this->enrollment->training->title ?? 'Training'),
            'url' => '/trainings/' . ($this->enrollment->training_id ?? ''),
            'icon' => 'graduation-cap',
            'type' => 'info',
        ];
    }
}
