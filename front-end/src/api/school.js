import axios from "axios";
import {BASE_PATH, getAuthedHeaders} from "@/api/api";


export function createApi(name) {
    return axios.post(BASE_PATH + '/schools/create', {name}, {
        headers: getAuthedHeaders()
    })
}

export function listApi(lastId = 0) {
    return axios({
        url: BASE_PATH + '/schools',
        headers: getAuthedHeaders(),
        params: {lastId}
    })
}

export function approveApi(schoolId) {
    return axios({
        method: 'put',
        url: BASE_PATH + `/schools/approve/${schoolId}/pass`,
        headers: getAuthedHeaders(),
    })
}
