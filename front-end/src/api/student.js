import {authedApi} from "@/api/api";

export function createStudentApi(schoolId, data) {
    return authedApi().post(`/schools/students/${schoolId}/create`, data)
}

export function getSchoolInfo() {
    return authedApi().get('/students/school-info')
}

export function getSchoolTeachers() {
    return authedApi().get(`/students/school-teachers`)
}

export function getFollowingTeachers() {
    return authedApi().get(`/students/following`)
}

export function followApi(teacherId) {
    return authedApi().post(`/students/follow/${teacherId}`)
}

export function unfollowApi(teacherId) {
    return authedApi().delete(`/students/unfollow/${teacherId}`)
}
