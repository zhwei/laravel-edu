import axios from "axios";
import {getAccessToken, logout} from "@/utils/auth";

export function unAuthedApi() {
    return axios.create({
        baseURL: process.env.VUE_APP_API_URL,
    })
}

export function authedApi() {
    const api = axios.create({
        baseURL: process.env.VUE_APP_API_URL,
        headers: {
            'Authorization': 'Bearer ' + getAccessToken(),
        }
    });

    api.interceptors.response.use((response) => response, (error) => {
        if (error.response.status === 401) {
            logout()
        }
        throw error;
    });

    return api
}
