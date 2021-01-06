import {authedApi} from "@/api/api";


export function createApi(name) {
    return authedApi().post('/schools/create', {name})
}

export function listApi(lastId = 0) {
    return authedApi().get('/schools', {
        params: {lastId}
    })
}

export function approveApi(schoolId) {
    return authedApi().put(`/schools/approve/${schoolId}/pass`)
}
