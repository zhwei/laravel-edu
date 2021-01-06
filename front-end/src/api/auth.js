import {unAuthedApi} from "@/api/api";

export function loginApi(data) {
    return unAuthedApi().post('/auth/login', data)
}

export function registerApi(data) {
    return unAuthedApi().post('/auth/register', data)
}
