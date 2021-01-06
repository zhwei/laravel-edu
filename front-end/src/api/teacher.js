import {authedApi} from "@/api/api";

export function listFollowingApi(lastId = 0) {
    return authedApi().get('/teachers/students/following', {
        params: {lastId}
    })
}

export function listTeachingApi(lastId = 0) {
    return authedApi().get('/teachers/students/teaching', {
        params: {lastId}
    })
}
