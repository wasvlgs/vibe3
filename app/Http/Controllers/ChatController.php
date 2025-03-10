<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(){
        $user = auth()->user();
        $onlineFriends = $user->friends()->where('isActive', true)->get();
        $conversations = $user->conversations()->where('user_one_id',$user->id)->orWhere('user_two_id',$user->id)->get();
        return view('chat.chat',compact('user','onlineFriends','conversations'));
    }
    public function show($pseudo)
    {
        $user = auth()->user();
        $friend = User::where('pseudo', $pseudo)->firstOrFail();
        $onlineFriends = $user->friends()->where('isActive','true')->get();
        $conversations = $user->conversations()->where('user_one_id',$user->id)->orWhere('user_two_id',$user->id)->get();

        // Find the conversation between the logged-in user and the selected friend
        $conversation = Conversation::where(function ($query) use ($user, $friend) {
            $query->where('user_one_id', $user->id)
                ->where('user_two_id', $friend->id);
        })->orWhere(function ($query) use ($user, $friend) {
            $query->where('user_one_id', $friend->id)
                ->where('user_two_id', $user->id);
        })->first();

        // If no conversation exists, create one
        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one_id' => $user->id,
                'user_two_id' => $friend->id,
            ]);
        }
        Message::markAsRead($conversation->id);

        // Get all messages in the conversation
        $messages = $conversation->messages()->orderBy('created_at', 'asc')->get();

        return view('chat.conversation', compact('conversation','user', 'messages', 'friend','onlineFriends','conversations'));
    }


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

        return redirect()->route('chat.show', ['pseudo' => $request->friend_pseudo]);
    }


    // Mark messages as read
    public static function markAsRead($conversation_id)
    {
        $user = auth()->user();
        $conversation = Conversation::findOrFail($conversation_id);

        // Mark all unread messages in this conversation as read
        $conversation->messages()
            ->where('user_id', '!=', $user->id)  // Mark only other users' messages as read
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return redirect()->route('chat.show', ['pseudo' => $conversation->userOne->pseudo]);
    }



}
