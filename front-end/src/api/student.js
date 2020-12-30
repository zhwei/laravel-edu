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

export function getSchoolInfo() {
    return axios({
        method: 'get',
        url: BASE_PATH + `/students/school-info`,
        headers: getAuthedHeaders(),
    })
}

export function getSchoolTeachers() {
    return axios({
        method: 'get',
        url: BASE_PATH + `/students/school-teachers`,
        headers: getAuthedHeaders(),
    })
}

export function getFollowingTeachers() {
    return axios({
        method: 'get',
        url: BASE_PATH + `/students/following`,
        headers: getAuthedHeaders(),
    })
}

export function followApi(teacherId) {
    return axios({
        method: 'post',
        url: BASE_PATH + `/students/follow/${teacherId}`,
        headers: getAuthedHeaders(),
    })
}

export function unfollowApi(teacherId) {
    return axios({
        method: 'delete',
        url: BASE_PATH + `/students/unfollow/${teacherId}`,
        headers: getAuthedHeaders(),
    })
}
