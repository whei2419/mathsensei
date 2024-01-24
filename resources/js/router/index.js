import { createRouter, createWebHashHistory } from "vue-router";
import Test from '../components/TestRoute.vue'
import login from '../pages/login.vue';

const routes = [
    {
        path:'/',
        component:login,
        name:'log-in'
    },
    {
        path:'/about',
        component:Test
    }
];

const router = createRouter({
    history:createWebHashHistory(),
    routes
});

export default router;
