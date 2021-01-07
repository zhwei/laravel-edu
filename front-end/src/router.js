import VueRouter from 'vue-router'

import {getAccessToken} from "@/utils/auth";

import Index from '@/pages/Index'
import Login from "@/pages/Login";
import Register from "@/pages/Register";
import SchoolList from "@/pages/SchoolList";
import SchoolCreation from "@/pages/SchoolCreation";
import StudentInfo from "@/pages/StudentInfo";
import StudentTeachers from "@/pages/StudentTeachers";
import StudentFollowing from "@/pages/StudentFollowing";
import TeacherFollowing from "@/pages/TeacherFollowing";
import TeacherTeaching from "@/pages/TeacherTeaching";
import LineLogin from "@/pages/LineLogin";


const requireAuth = function (to, from, next) {
    if (getAccessToken()) {
        next()
    } else {
        next('/login')
    }
}

export default new VueRouter({
    routes: [
        {path: '/login', component: Login},
        {path: '/register', component: Register},
        {path: '/line', component: LineLogin},

        {path: '/schools/list', component: SchoolList, beforeEnter: requireAuth},
        {path: '/schools/create', component: SchoolCreation, beforeEnter: requireAuth},
        {path: '/teachers/teaching', component: TeacherTeaching, beforeEnter: requireAuth, props: true},
        {path: '/teachers/following', component: TeacherFollowing, beforeEnter: requireAuth, props: true},
        {path: '/students/info', component: StudentInfo, beforeEnter: requireAuth},
        {path: '/students/teaching', component: StudentTeachers, beforeEnter: requireAuth},
        {path: '/students/following', component: StudentFollowing, beforeEnter: requireAuth},

        {path: '*', component: Index, beforeEnter: requireAuth},
    ],
})
