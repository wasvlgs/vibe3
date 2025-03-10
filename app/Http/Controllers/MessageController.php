<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Events\MessageSent;
use App\Models\Conversation;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function sendMessage(Request $request, $conversation_id)
{
    $request->validate([
        'content' => 'required|string',
    ]);

    $user = auth()->user();
    $conversation = Conversation::findOrFail($conversation_id);

    // Check if the user is part of the conversation
    if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
        abort(403, 'Unauthorized action.');
    }

    // Store the new message
    $message = Message::create([
        'user_id' => $user->id,
        'conversation_id' => $conversation->id,
        'content' => $request->content,
    ]);

    // Broadcast the message
    broadcast(new MessageSent($message)); // Broadcast the message

    // Retrieve friend's pseudo and pass it along in the redirect
    $friend = $conversation->user_one_id == $user->id ? $conversation->userTwo : $conversation->userOne;

    return redirect()->route('chat.show', ['pseudo' => $friend->pseudo]);
}

}
