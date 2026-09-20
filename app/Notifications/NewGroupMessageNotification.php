<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewGroupMessageNotification extends Notification
{
    use Queueable;

    public function __construct(public $group, public $sender, public string $body) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'        => 'new_group_message',
            'title'       => 'New message in ' . $this->group->name,
            'message'     => substr($this->body, 0, 100),
            'sender_name' => $this->sender->username,
            'group_id'    => $this->group->id,
            'group_name'  => $this->group->name,
            'action_url'  => route('communication.groups-show', $this->group->id),
            'created_at'  => now()->toISOString(),
        ];
    }
}