<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Messages') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-4/5 mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="flex h-[calc(100vh-200px)]">
                    <!-- Left sidebar - Contacts -->
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
                                @foreach ($conversations as $conversation)
                                    {{-- Determine the other user in the conversation --}}
                                    @php
                                        $him = $conversation->userOne->id !== $user->id ? $conversation->userOne : $conversation->userTwo;
                                    @endphp
                                    <a href="/chat/{{ $him->pseudo }}" class="flex items-center p-3 hover:bg-gray-100 cursor-pointer {{ $loop->first ? 'bg-gray-100' : '' }}" data-conversation-id="{{ $conversation->id }}">
                                        <div class="relative">
                                            {{-- Use the user's actual avatar or default to a placeholder --}}
                                            <img src="{{ $him->avatar ? asset('uploads/avatars/' . $him->avatar) : asset('uploads/cover_picture/none.png') }}" class="w-12 h-12 rounded-full" alt="User Avatar">
                                            <div class="absolute bottom-0 right-0 w-3 h-3 {{ $him->is_active ? 'bg-green-500' : 'bg-gray-300' }} rounded-full border-2 border-white"></div>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <div class="flex justify-between items-center">
                                                <span class="font-medium">{{ $him->name }} </span>
                                                <span class="text-xs text-gray-500">{{ $conversation->messages->last() ? $conversation->messages->last()->created_at->diffForHumans() : 'No messages' }}</span>
                                            </div>
                                            <p class="text-sm text-gray-500 truncate">{{ $conversation->messages->last() ? $conversation->messages->last()->content : 'No preview' }}</p>
                                        </div>
                                        @if($conversation->unread_messages_count > 0)
                                            <div class="w-5 h-5 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs">
                                                {{ $conversation->unread_messages_count }}
                                            </div>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>

                    </div>

                    <!-- Right sidebar - Chat messages -->
                    <div class="flex-1 p-4">
                        <div class="chat-header">
                            <h3 class="font-medium">Select a conversation</h3>
                        </div>
                        <div id="messagesContainer" class="flex flex-col space-y-4 overflow-y-auto h-[calc(100vh-250px)]">
                            <!-- Messages will be loaded dynamically here -->
                        </div>
                        <div class="flex items-center mt-4">
                            <input type="text" placeholder="Type a message..." class="w-full p-2 border border-gray-300 rounded-md mr-2">
                            <button class="bg-blue-500 text-white p-2 rounded-md">Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
