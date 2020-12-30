import axios from "axios";
import {BASE_PATH, getAuthedHeaders} from "@/api/api";

export function listFollowingApi(lastId = 0) {
    return axios({
        url: BASE_PATH + '/teachers/students/following',
        headers: getAuthedHeaders(),
        params: {lastId}
    })
}

export function listTeachingApi(lastId = 0) {
    return axios({
        url: BASE_PATH + '/teachers/students/teaching',
        headers: getAuthedHeaders(),
        params: {lastId}
    })
}
