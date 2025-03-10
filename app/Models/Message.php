<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'conversation_id',
        'content',
        'is_read',
    ];

    // Relationship to the user who sent the message
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to the conversation
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
    public static function markAsRead($messageId)
    {
        $message = self::find($messageId);
        if ($message) {
            $message->is_read = true;
            $message->save();
        } else {
            return response()->json(['error' => 'Message not found'], 404);
        }
    }
}
