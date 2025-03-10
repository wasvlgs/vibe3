import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const echo = new Echo({
    broadcaster: 'pusher',
    key: 'b1bbea45679ee1944f56',
    cluster: 'eu',
    forceTLS: true
});

const conversationId = 2;
Pusher.logToConsole = true;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY, // Updated
    cluster: 'eu',
    wsHost: '127.0.0.1',
    wsPort: 8000,
    forceTLS: true, // or false, depending on whether you're using SSL
    enableStats: false,
});
// window.Echo.private(`conversation.{{ $conversation->id }}`)
//     .listen('MessageSent', (e) => {
//         console.log('New message:', e.message);
//     });
window.Echo.private(`conversation.${conversationId}`)
    .listen('MessageSent', (event) => {
        console.log(event);
        console.log("hahuwa message",event.message.content)
        // Add the new message to the message list (append it dynamically)
        const messageContainer = document.querySelector('.messages-container');
        const messageHtml = `
            <div class="mb-4 text-left">
                <span class="text-xs text-gray-500">${event.message.created_at}</span>
                <div class="bg-gray-200 inline-block px-4 py-2 rounded-lg">
                    <p class="text-sm">${event.message.content}</p>
                </div>
            </div>
        `;
        messageContainer.innerHTML += messageHtml;
        messageContainer.scrollTop = messageContainer.scrollHeight;
    });



window.Pusher = require('pusher-js');

echo.channel('online-users')
    .listen('UserOnline', (event) => {
        console.log(event.user);
        // Update online status in the UI
    });

    