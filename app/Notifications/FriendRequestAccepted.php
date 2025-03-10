<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class FriendRequestAccepted extends Notification
{
    public $sender;

    public function __construct($sender)
    {
        $this->sender = $sender;
    }

    public function via($notifiable)
    {
        return ['broadcast'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'sender_name' => $this->sender->name,
            'message' => 'Your friend request has been accepted.',
        ]);
    }

    public function toArray($notifiable)
    {
        return [
            'sender_name' => $this->sender->name,
            'message' => 'Your friend request has been accepted.',
        ];
    }

}
