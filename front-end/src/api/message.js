import {authedApi} from "@/api/api";


export function studentTalk(teacherId, content) {
    return authedApi().post(`/messages/student-talk/${teacherId}`, {content})
}

export function teacherTalk(studentId, content) {
    return authedApi().post(`/messages/teacher-talk/${studentId}`, {content})
}
