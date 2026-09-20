<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Announcement;

class NewAnnouncementNotification extends Notification
{
    use Queueable;

    public function __construct(public Announcement $announcement) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'            => 'new_announcement',
            'title'           => 'New Announcement: ' . $this->announcement->title,
            'message'         => substr($this->announcement->body, 0, 100),
            'priority'        => $this->announcement->priority,
            'announcement_id' => $this->announcement->id,
            'action_url'      => route('communication.announcements-show', $this->announcement->id),
            'created_at'      => now()->toISOString(),
        ];
    }
}