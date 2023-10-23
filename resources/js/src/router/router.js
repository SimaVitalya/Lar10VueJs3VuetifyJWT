import { createRouter, createWebHistory } from 'vue-router';
import routes from './routes.js';

const router = createRouter({
	history: createWebHistory(),
	linkActiveClass: 'active',
	routes,
});

export default router;
router.beforeEach((to, from, next) => {
    const accessToken = localStorage.getItem('access_token')
    if (!accessToken) {
        if (to.name === 'user.login' || to.name === 'user.registration') {
            return next()
        } else if ( to.name === 'user.personal') {
            return next({
                name: 'user.login'
            })
        } else {
            return next()
        }
    } else if (to.name === 'user.login' || to.name === 'user.registration') {
        return next({
            name: 'user.personal'
        })
    }
    next()

})
