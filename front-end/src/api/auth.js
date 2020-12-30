import axios from "axios";
import {BASE_PATH} from "@/api/api";

export function loginApi(data) {
    return axios.post(BASE_PATH + '/auth/login', data)
}

export function registerApi(data) {
    return axios.post(BASE_PATH + '/auth/register', data)
}
