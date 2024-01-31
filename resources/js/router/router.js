import { createRouter, createWebHashHistory } from "vue-router";
import Login from '../pages/Login.vue';
import Game from '../pages/Game.vue';
import Home from '../pages/Home.vue';
import Index from '../index.vue';

const routes = [
    {
        path: '/',
        component: Index,
        name: 'index'
    },
    {
        path: '/login',
        component: Login,
        name: 'Login'
    },
    {
        path: '/game',
        component: Game,
        name: 'Game',
        beforeEnter: (to, from, next) => {
            if (isStart()) {
                next();
            } else {
                next({ name: 'index' });
            }
        }
    },
    {
        path: '/home',
        component: Home,
        name: 'Home'
    }
];

const router = createRouter({
    history: createWebHashHistory(),
    routes
});

function isStart() {
    const isStart = localStorage.getItem('isStart');
    return isStart === 'true';
}

function isAuthenticated() {
    const token = localStorage.getItem('token');
    return !!token;
}

router.beforeEach((to, from, next) => {
    if (to.name !== 'Login' && !isAuthenticated()) {
        next({ name: 'Login' });
    } else {
        next();
    }
});

export default router;
