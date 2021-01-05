import Echo from 'laravel-echo'
import Pusher from "pusher-js";


export function createEcho() {
    Pusher.logToConsole = process.env.NODE_ENV === 'development'

    return new Echo({
        broadcaster: 'pusher',
        key: process.env.VUE_APP_PUSHER_APP_KEY,
        cluster: 'mt1',
        forceTLS: true
    });
}
