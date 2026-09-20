<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Message;

class NewMessageNotification extends Notification
{
    use Queueable;

    public function __construct(public Message $message) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'        => 'new_message',
            'title'       => 'New Message from ' . ($this->message->sender?->username ?? 'Unknown'),
            'message'     => $this->message->subject ?? 'You have a new message',
            'preview'     => substr($this->message->body, 0, 100),
            'message_id'  => $this->message->id,
            'sender_id'   => $this->message->sender_id,
            'sender_name' => $this->message->sender?->username,
            'action_url'  => route('communication.message-show', $this->message->id),
            'created_at'  => now()->toISOString(),
        ];
    }
}