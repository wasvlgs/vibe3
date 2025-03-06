import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

let userId = 1;

window.Echo.channel('test-channel')
    .listen('.TestEvent', (data) => { // ✅ Ensure `data` is passed here
        console.log("📩 New event received:", data);
        alert(`📩 New event: ${data.message}`);
    });

