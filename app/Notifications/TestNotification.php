<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TestNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $url;

    public function __construct(string $message, string $url = null)
    {
        $this->message = $message;
        $this->url = $url;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Test Notification',
            'message' => $this->message,
            'url' => $this->url,
            'icon' => 'bell',
            'type' => 'info',
        ];
    }
}
