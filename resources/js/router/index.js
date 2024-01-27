import { createRouter, createWebHashHistory } from "vue-router";
import login from '../pages/login.vue';
import Game from '../pages/Game.vue';

const routes = [
    {
        path:'/',
        component:Game,
        name:'game'
    },
];

const router = createRouter({
    history:createWebHashHistory(),
    routes
});

export default router;
