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
                                @for ($i = 1; $i <= 8; $i++)
                                    <div class="flex flex-col items-center">
                                        <div class="relative">
                                            <img src="{{ asset('uploads/cover_picture/none.png') }}" class="w-12 h-12 rounded-full" alt="Friend {{$i}}">
                                            <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
                                        </div>
                                        <span class="text-xs mt-1 whitespace-nowrap">User {{$i}}</span>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <!-- Conversations List -->
                        <div class="flex-1 overflow-y-auto">
                            <h3 class="text-sm font-semibold text-gray-500 p-4 pb-2">RECENT CHATS</h3>
                            <div class="space-y-1">
                                @for ($i = 1; $i <= 15; $i++)
                                    <div class="flex items-center p-3 hover:bg-gray-100 cursor-pointer {{ $i == 1 ? 'bg-gray-100' : '' }}">
                                        <div class="relative">
                                            <img src="{{ asset('uploads/cover_picture/none.png') }}" class="w-12 h-12 rounded-full" alt="User Avatar">
                                            <div class="absolute bottom-0 right-0 w-3 h-3 {{ $i % 3 == 0 ? 'bg-green-500' : 'bg-gray-300' }} rounded-full border-2 border-white"></div>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <div class="flex justify-between items-center">
                                                <span class="font-medium">Friend Name {{ $i }}</span>
                                                <span class="text-xs text-gray-500">{{ rand(1, 59) }}m</span>
                                            </div>
                                            <p class="text-sm text-gray-500 truncate">{{ $i % 2 == 0 ? 'You: ' : '' }}This is a preview of the last message in this conversation...</p>
                                        </div>
                                        @if($i % 4 == 0)
                                            <div class="w-5 h-5 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs">
                                                {{ rand(1, 9) }}
                                            </div>
                                        @endif
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <!-- Right side - Chat area -->
                    <div class="w-2/3 flex flex-col">
                        <!-- Chat Header -->
                        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="relative">
                                    <img src="{{ asset('uploads/cover_picture/none.png') }}" class="w-10 h-10 rounded-full" alt="User">
                                    <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
                                </div>
                                <div class="ml-3">
                                    <div class="font-medium">Friend Name</div>
                                    <div class="text-xs text-green-500">Online</div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button class="p-2 rounded-full hover:bg-gray-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </button>
                                <button class="p-2 rounded-full hover:bg-gray-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </button>
                                <button class="p-2 rounded-full hover:bg-gray-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Messages Area -->
                        <div class="flex-1 p-4 overflow-y-auto bg-gray-50" id="messagesContainer">
                            <!-- Date Separator -->
                            <div class="flex justify-center mb-4">
                                <div class="bg-gray-200 text-gray-500 text-xs px-2 py-1 rounded-full">Today</div>
                            </div>

                            <!-- Messages -->
                            @for ($i = 1; $i <= 10; $i++)
                                @if ($i % 2 == 0)
                                    <!-- Outgoing Message -->
                                    <div class="flex justify-end mb-4">
                                        <div class="bg-blue-500 text-white rounded-lg py-2 px-4 max-w-md">
                                            <p>This is an outgoing message. It could be a bit longer to test how wrapping works.</p>
                                            <div class="text-xs text-blue-100 text-right mt-1">{{ rand(1, 12) }}:{{ sprintf('%02d', rand(0, 59)) }} {{ rand(0, 1) ? 'AM' : 'PM' }}</div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Incoming Message -->
                                    <div class="flex mb-4">
                                        <img src="{{ asset('uploads/cover_picture/none.png') }}" class="w-8 h-8 rounded-full mr-2" alt="User">
                                        <div class="bg-white rounded-lg py-2 px-4 max-w-md shadow-sm">
                                            <p>This is an incoming message. The content of this message is from your friend.</p>
                                            <div class="text-xs text-gray-500 mt-1">{{ rand(1, 12) }}:{{ sprintf('%02d', rand(0, 59)) }} {{ rand(0, 1) ? 'AM' : 'PM' }}</div>
                                        </div>
                                    </div>
                                @endif
                            @endfor

                            <!-- Typing Indicator (optional) -->
                            <div class="flex mb-4">
                                <img src="{{ asset('uploads/cover_picture/none.png') }}" class="w-8 h-8 rounded-full mr-2" alt="User">
                                <div class="bg-white rounded-lg py-2 px-4 shadow-sm">
                                    <div class="flex space-x-1">
                                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Message Input Area -->
                        <div class="p-4 border-t border-gray-200 bg-white">
                            <div class="flex items-center">
                                <button class="p-2 rounded-full hover:bg-gray-100 mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                                <button class="p-2 rounded-full hover:bg-gray-100 mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </button>
                                <div class="flex-1">
                                    <input type="text" class="w-full border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Type a message...">
                                </div>
                                <button class="ml-2 p-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Scroll to bottom of messages container
            const messagesContainer = document.getElementById('messagesContainer');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            // Simulate message sending when pressing Enter
            const messageInput = document.querySelector('input[placeholder="Type a message..."]');
            messageInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && this.value.trim() !== '') {
                    sendMessage(this.value);
                    this.value = '';
                }
            });

            // Send button click
            const sendButton = document.querySelector('button.bg-blue-500');
            sendButton.addEventListener('click', function() {
                const input = document.querySelector('input[placeholder="Type a message..."]');
                if (input.value.trim() !== '') {
                    sendMessage(input.value);
                    input.value = '';
                }
            });

            // Function to add a new message to the UI
            function sendMessage(text) {
                const messagesContainer = document.getElementById('messagesContainer');

                // Create message element
                const messageDiv = document.createElement('div');
                messageDiv.className = 'flex justify-end mb-4';

                // Get current time
                const now = new Date();
                const hours = now.getHours() % 12 || 12;
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const ampm = now.getHours() >= 12 ? 'PM' : 'AM';
                const timeString = `${hours}:${minutes} ${ampm}`;

                // Set message content
                messageDiv.innerHTML = `
                    <div class="bg-blue-500 text-white rounded-lg py-2 px-4 max-w-md">
                        <p>${text}</p>
                        <div class="text-xs text-blue-100 text-right mt-1">${timeString}</div>
                    </div>
                `;

                messagesContainer.appendChild(messageDiv);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;

                // In a real application, you would send this to your backend via AJAX
                // and then handle the response
            }

            // Make conversations clickable
            const conversations = document.querySelectorAll('.flex.items-center.p-3');
            conversations.forEach(conversation => {
                conversation.addEventListener('click', function() {
                    // Remove active class from all conversations
                    conversations.forEach(c => c.classList.remove('bg-gray-100'));

                    // Add active class to clicked conversation
                    this.classList.add('bg-gray-100');

                    // Get friend name from the clicked conversation
                    const friendName = this.querySelector('.font-medium').textContent;

                    // Update chat header with friend name
                    document.querySelector('.chat-header .font-medium').textContent = friendName;

                    // In a real app, you would load the messages for this conversation from the backend
                });
            });
        });
    </script>
</x-app-layout>
