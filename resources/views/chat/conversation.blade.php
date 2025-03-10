<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Conversation with ' . $friend->name) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-4/5 mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="flex h-[calc(100vh-200px)]">
                    <!-- Left Sidebar - Online Friends -->
                    <div class="w-1/3 border-r border-gray-200 flex flex-col">
                        <!-- Search Bar -->
                        <div class="p-4 border-b border-gray-200">
                            <div class="relative">
                                <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-md pl-10" placeholder="Search messages...">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Online Friends -->
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-500 mb-3">ONLINE FRIENDS</h3>
                            <div class="flex space-x-2 overflow-x-auto pb-2">
                                @foreach ($onlineFriends as $friend)
                                    <a href="/chat/{{ $friend->pseudo }}" class="flex flex-col items-center">
                                        <div class="relative">
                                            <img src="{{ asset('uploads/cover_picture/none.png') }}" class="w-12 h-12 rounded-full" alt="Friend {{ $friend->name }}">
                                            <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
                                        </div>
                                        <span class="text-xs mt-1 whitespace-nowrap">{{ $friend->name }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Conversations List -->
                        <div class="flex-1 overflow-y-auto">
                            <h3 class="text-sm font-semibold text-gray-500 p-4 pb-2">RECENT CHATS</h3>
                            <div class="space-y-1">
                                @foreach ($conversations as $conv)
                                    @php
                                        $him = $conv->userOne->id !== $user->id ? $conv->userOne : $conv->userTwo;
                                    @endphp
                                    <a href="/chat/{{ $him->pseudo }}" class="flex items-center p-3 hover:bg-gray-100 cursor-pointer {{ $loop->first ? 'bg-gray-100' : '' }}" data-conversation-id="{{ $conv->id }}">
                                        <div class="relative">
                                            <img src="{{ $him->avatar ? asset('uploads/avatars/' . $him->avatar) : asset('uploads/cover_picture/none.png') }}" class="w-12 h-12 rounded-full" alt="User Avatar">
                                            <div class="absolute bottom-0 right-0 w-3 h-3 {{ $him->is_active ? 'bg-green-500' : 'bg-gray-300' }} rounded-full border-2 border-white"></div>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <div class="flex justify-between items-center">
                                                <span class="font-medium">{{ $him->name }} </span>
                                                <span class="text-xs text-gray-500">{{ $conv->messages->last() ? $conv->messages->last()->created_at->diffForHumans() : 'No messages' }}</span>
                                            </div>
                                            <p class="text-sm text-gray-500 truncate">
                                                @if ($conv->messages->last())
                                                    {{ $conv->messages->last()->user->name }}: {{ $conv->messages->last()->content }}
                                                @else
                                                    No preview
                                                @endif
                                            </p>
                                        </div>
                                        @if($conv->unread_messages_count > 0)
                                            <div class="w-5 h-5 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs">
                                                {{ $conv->unread_messages_count }}
                                            </div>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Right Sidebar - Chat with Current Friend -->
                    <div class="flex-1 p-4 flex flex-col">
                        <!-- Conversation Header -->
                        <div class="flex items-center justify-between border-b pb-3 mb-4">
                            <div class="flex items-center">
                                <img src="{{ $friend->avatar ? asset('uploads/avatars/' . $friend->avatar) : asset('uploads/cover_picture/none.png') }}" class="w-12 h-12 rounded-full" alt="{{ $friend->name }}">
                                <div class="ml-3">
                                    <span class="font-medium">{{ $friend->name }}</span>
                                    <div class="text-sm text-gray-500">{{ $friend->isActive ? 'Online' : 'Offline' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Messages Section -->
                        <div class="flex-1 overflow-y-auto h-[calc(100vh-250px)] messages-container">
                            @foreach ($messages as $message)
                                <div class="mb-4 {{ $message->user_id === auth()->id() ? 'text-right' : 'text-left' }}">
                                    <span class="text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</span>
                                    <div class="{{ $message->user_id === auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-200' }} inline-block px-4 py-2 rounded-lg">
                                        <p class="text-sm">{{ $message->content }}</p>
                                    </div>
                                    @if($message->user_id === auth()->id())
                                        @if ($message->is_read)
                                            <span class="text-gray-500">Seen</span>
                                        @else
                                            <span class="text-gray-500">Sent</span>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </div>


                        <!-- Message Input Section -->
                        <form action="{{ route('chat.sendMessage', ['conversation_id' => $conversation->id]) }}" method="POST" class="flex items-center mt-4">
                            @csrf
                            <input type="text" name="content" class="w-full p-2 border border-gray-300 rounded-md mr-2" placeholder="Type a message..." required>
                            <input type="hidden" name="friend_pseudo" value="{{ $friend->pseudo }}">
                            <button type="submit" class="bg-blue-500 text-white p-2 rounded-md">Send</button>
                            <div id="chat-container" data-conversation-id="{{ $conversation->id }}"></div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

const userId = document.querySelector('meta[name="user-id"]').content; // Get logged-in user ID
const conversationId = document.querySelector('meta[name="conversation-id"]').content; // Get current conversation ID

window.Echo.private(`chat.${conversationId}`)
    .listen('MessageSent', (event) => {
        console.log('New message:', event);
        // let chatBox = document.getElementById("chat-box");
        // chatBox.innerHTML += `<p><strong>${event.user}:</strong> ${event.message}</p>`;
    });

document.getElementById("send-button").addEventListener("click", function () {
    let messageInput = document.getElementById("message-input");
    fetch("/api/messages/send", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Authorization": `Bearer ${userToken}`, // If using Sanctum/Auth
        },
        body: JSON.stringify({
            conversation_id: conversationId,
            content: messageInput.value,
        }),
    })
        .then(response => response.json())
        .then(data => {
            console.log("Message sent:", data);
            messageInput.value = "";
        });
});

        // const conversationId = "{{ $conversation->id }}";
        // console.log(conversationId)// Pass this from Blade to JS
        // window.onload = function () {
        //     let messagesContainer = document.querySelector('.messages-container');
        //     if (messagesContainer) {
        //         messagesContainer.scrollTop = messagesContainer.scrollHeight;
        //     }
        // }
        // window.Echo.private('private-conversation.' + conversationId)
        //     .listen('MessageSent', (event) => {
        //         console.log(event);
        //         // Handle the incoming message
        //     });
    </script>
</x-app-layout>
