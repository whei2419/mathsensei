import { createRouter, createWebHashHistory } from "vue-router";
import login from '../pages/login.vue';
import Game from '../pages/Game.vue';

const routes = [
    {
        path:'/',
        component:login,
        name:'Login'
    },
    {
        path:'/game',
        component:Game,
        name:'Game'
    },
];

const router = createRouter({
    history:createWebHashHistory(),
    routes
});

export default router;
