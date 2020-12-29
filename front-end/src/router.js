import VueRouter from 'vue-router'

import Login from "@/components/Login";
import Register from "@/components/Register";
import HelloWorld from "@/components/HelloWorld";

const isAuthed = () => false;
const requireAuth = function (to, from, next) {
    if (isAuthed()) {
        next()
    } else {
        next({name: 'login'})
    }
}

export default new VueRouter({
    routes: [

        {name: 'login', path: '/login', component: Login},
        {name: 'register', path: '/register', component: Register},

        {name: 'index', path: '/', component: HelloWorld, beforeEnter: requireAuth},
    ],
})
