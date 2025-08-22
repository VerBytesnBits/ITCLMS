import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

window.Echo.channel("units")
    .listen(".UnitCreated", (e) => {
        console.log("UnitCreated received", e);
        Livewire.dispatch("UnitCreated", e);
    })
    .listen(".UnitUpdated", (e) => {
        console.log("UnitUpdated received", e);
        Livewire.dispatch("UnitUpdated", e);
    })
    .listen(".UnitDeleted", (e) => {
        console.log("UnitDeleted received", e);
        Livewire.dispatch("UnitDeleted", e);
    });
