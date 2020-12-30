import VueRouter from 'vue-router'

import Index from '@/components/Index'
import Login from "@/components/Login";
import Register from "@/components/Register";
import {getAccessToken} from "@/utils/auth";

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

        {path: '/', component: Index, beforeEnter: requireAuth},
    ],
})
