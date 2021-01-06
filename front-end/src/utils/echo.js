import Echo from 'laravel-echo'
import Pusher from "pusher-js";
import {getAccessToken} from "@/utils/auth";


export function createEcho() {
    Pusher.logToConsole = process.env.NODE_ENV === 'development'

    return new Echo({
        broadcaster: 'pusher',
        key: process.env.VUE_APP_PUSHER_APP_KEY,
        cluster: 'mt1',
        forceTLS: true,
        authEndpoint: process.env.VUE_APP_API_URL + '/broadcasting/auth',
        auth: {
            headers: {
                'Authorization': 'Bearer ' + getAccessToken(),
            }
        }
    });
}
