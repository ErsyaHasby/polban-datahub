import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import Login from '../pages/LoginPage.vue'
import Dashboard from '../pages/Dashboard.vue'
import AdminReview from '../pages/AdminReview.vue'
import AdminLogs from '../pages/AdminLogs.vue'
import NotFound from '../pages/NotFound.vue'
import DownloadData from '../pages/DownloadData.vue'
import MyUploads from '../pages/MyUploads.vue'
import ImportDataPage from '../pages/ImportDataPage.vue'

const routes = [
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: { guest: true }
    },
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard,
        meta: { requiresAuth: true }
    },
    {
        path: '/my-uploads',
        name: 'my-uploads',
        component: MyUploads,
        meta: { requiresAuth: true, role: 'participant' }
    },
    {
        path: '/import-data',
        name: 'import-data',
        component: ImportDataPage,
        meta: { requiresAuth: true, role: 'participant' } 
    },
    {
        path: '/admin/review',
        name: 'admin-review',
        component: AdminReview,
        meta: { requiresAuth: true, role: 'admin' }
    },
    {
        path: '/admin/logs',
        name: 'admin-logs',
        component: AdminLogs,
        meta: { requiresAuth: true, role: 'admin' }
    },
    {
        path: '/download-data',
        name: 'download-data',
        component: DownloadData,
        meta: { requiresAuth: true } 
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        component: NotFound
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

// === STRICT NAVIGATION GUARD ===
router.beforeEach((to, from, next) => {
    const authStore = useAuthStore()

    // Cek apakah halaman butuh login
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth)
    const guestOnly = to.matched.some(record => record.meta.guest) // Halaman Login
    const requiredRole = to.meta.role

    // KARENA TOKEN TIDAK DISIMPAN DI LOCALSTORAGE:
    // Saat refresh, authStore.isLoggedIn otomatis FALSE.
    
    if (requiresAuth && !authStore.isLoggedIn) {
        // Jika butuh login tapi belum login -> Tendang ke Login
        next({ name: 'login' })
    } 
    else if (guestOnly && authStore.isLoggedIn) {
        // Jika halaman login tapi sudah login -> Tendang ke Dashboard
        next({ name: 'dashboard' })
    } 
    else if (requiredRole && authStore.user?.role !== requiredRole) {
        // Jika role salah -> Tendang ke Dashboard
        next({ name: 'dashboard' })
    } 
    else {
        // Lanjut
        next()
    }
})

export default router