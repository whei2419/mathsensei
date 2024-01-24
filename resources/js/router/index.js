import { createRouter, createWebHashHistory } from "vue-router";
import Login from '../pages/Login.vue'
import GameView from '../pages/Game.vue';

const routes = [
    {
        path:'/',
        component:GameView
    },
];

const router = createRouter({
    history:createWebHashHistory(),
    routes
});

export default router;
