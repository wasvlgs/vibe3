<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_one_id',
        'user_two_id',
    ];

    // Relationship to messages in the conversation
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Relationship to the first user in the conversation
    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    // Relationship to the second user in the conversation
    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }
}
