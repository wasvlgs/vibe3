<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\BroadcastEvent;
use Illuminate\Queue\SerializesModels;

class MessageSent extends BroadcastEvent
{
    use InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('private-chat.' . $this->message->conversation_id);
    }

    public function broadcastAs()
    {
        return 'MessageSent';
    }
}
