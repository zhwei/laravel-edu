import {getAccessToken} from "@/utils/auth";

export const BASE_PATH = process.env.VUE_APP_API_URL

export function getAuthedHeaders() {
    const token = getAccessToken()
    if (token) {
        return {
            'Authorization': 'Bearer ' + token,
        }
    }
}
