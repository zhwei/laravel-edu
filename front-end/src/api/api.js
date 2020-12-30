import {getAccessToken} from "@/utils/auth";

export const BASE_PATH = 'http://127.0.0.1:8000'

export function getAuthedHeaders() {
    const token = getAccessToken()
    if (token) {
        return {
            'Authorization': 'Bearer ' + token,
        }
    }
}
