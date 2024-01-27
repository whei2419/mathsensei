import { createRouter, createWebHashHistory } from "vue-router";
import login from '../pages/login.vue';
import Game from '../pages/Game.vue';
import Home from '../pages/Home.vue';
import Index from '../index.vue'

const routes = [
    {
        path:'/',
        component:Index,
        name:'index'
    },
    {
        path:'/login',
        component:login,
        name:'Login'
    },
    {
        path:'/game',
        component:Game,
        name:'Game'
    },
    {
        path:'/Home',
        component:Home,
        name:'home'
    }
];

const router = createRouter({
    history:createWebHashHistory(),
    routes
});

export default router;
