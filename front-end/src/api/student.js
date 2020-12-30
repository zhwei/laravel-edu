import axios from "axios";
import {BASE_PATH, getAuthedHeaders} from "@/api/api";

export function createStudentApi(schoolId, data) {
    return axios({
        method: 'post',
        url: BASE_PATH + `/schools/students/${schoolId}/create`,
        headers: getAuthedHeaders(),
        data,
    })
}
