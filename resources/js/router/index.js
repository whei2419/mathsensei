import { createRouter, createWebHashHistory } from "vue-router";
import ExampleComponent from '../components/ExampleComponent.vue';
import Test from '../components/TestRoute.vue'

const routes = [
    {
        path:'/',
        component:ExampleComponent
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
