import Home from '@/pages/Home.vue';
import Comment from '@/pages/Comment.vue';
import Login from '@/User/Login.vue';
import Registration from '@/User/Registration.vue';
import Personal from "@/User/Personal.vue";
import NotFound from "@/pages/NotFound.vue";

const routes = [
    {
        path: '/',
        component: Home,
    },

    {
        path: '/comment',
        component: Comment,
    },
    {
        path: '/users/login',
        name: 'user.login',
        component: Login,
    },
    {
        path: '/comment',
        component: Comment,
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
        path: '/:catchAll(.*)',
        name: 'NotFound',
        component: NotFound
    },
];

export default routes;
