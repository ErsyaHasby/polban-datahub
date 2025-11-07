import { createRouter, createWebHistory } from 'vue-router'
import Home from '../pages/Home.vue'
import Login from '../pages/LoginPage.vue'

const routes = [
    // { path: '/', name: 'home', component: Home },
    { path: '/', name: 'login', component: Login },
]

export default createRouter({
    history: createWebHistory(),
    routes,
})
