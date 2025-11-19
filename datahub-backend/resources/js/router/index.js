// File: resources/js/router/index.js

import { createRouter, createWebHistory } from 'vue-router';

// Pastikan kamu sudah punya file Login.vue dan Dashboard.vue
// Kalau file Login.vue kamu masih di folder 'views', pindahkan dulu ke 'resources/js/pages/'
// atau sesuaikan path import di bawah ini.
import Login from '../pages/Login.vue'; 
import Dashboard from '../pages/Dashboard.vue';

const routes = [
    {
        path: '/',
        name: 'login',
        component: Login,
        meta: { guest: true }
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: { requiresAuth: true }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// Cek token sebelum pindah halaman
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token');

    if (to.meta.requiresAuth && !token) {
        // Kalau butuh login tapi gak ada token, lempar ke login
        next({ name: 'login' });
    } else if (to.meta.guest && token) {
        // Kalau halaman tamu (login) tapi sudah ada token, lempar ke dashboard
        next({ name: 'dashboard' });
    } else {
        next();
    }
});

export default router;