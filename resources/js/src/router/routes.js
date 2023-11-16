import Home from '@/pages/Home.vue';
import Login from '@/User/Login.vue';
import Registration from '@/User/Registration.vue';
import Personal from "@/User/Personal.vue";
import NotFound from "@/pages/NotFound.vue";
import Post from "@/pages/Post.vue";

const routes = [
    {
        path: '/',
        component: Home,
    },
    {
        path: '/users/login',
        name: 'user.login',
        component: Login,
    },
    {
        path: '/users/registration',
        name: 'user.registration',
        component: Registration,
    },
    {
        path: '/users/personal',
        name: 'user.personal',
        component: Personal,
    },
    {
        path: '/post',
        name: 'post',
        component: Post,
    },
    {
        path: '/:catchAll(.*)',
        name: 'NotFound',
        component: NotFound
    },
];

export default routes;
