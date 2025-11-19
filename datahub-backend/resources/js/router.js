// import { createRouter, createWebHistory } from 'vue-router';
// import Login from './views/Login.vue';
// import Dashboard from './views/Dashboard.vue';

// const routes = [
//     {
//         path: '/',
//         redirect: '/login'
//     },
//     {
//         path: '/login',
//         name: 'Login',
//         component: Login
//     },
//     {
//         path: '/dashboard',
//         name: 'Dashboard',
//         component: Dashboard,
//         beforeEnter: (to, from, next) => {
//             if (!localStorage.getItem('token')) {
//                 next({ name: 'Login' });
//             } else {
//                 next();
//             }
//         }
//     }
// ];

// const router = createRouter({
//     history: createWebHistory(),
//     routes
// });

// export default router;
