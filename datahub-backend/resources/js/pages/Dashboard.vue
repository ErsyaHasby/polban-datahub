<script setup>
import { ref, onMounted } from 'vue';
import axios from '../axios'; // Sesuaikan path ke file axios.js kamu
import { useRouter } from 'vue-router';

const router = useRouter();
const user = ref(null);
const message = ref('Memuat data...');

onMounted(async () => {
    try {
        // Panggil endpoint /me untuk memastikan token valid
        const res = await axios.get('/me');
        user.value = res.data;
        message.value = `Selamat datang, ${user.value.name}!`;
    } catch (error) {
        message.value = 'Sesi berakhir. Silakan login kembali.';
        localStorage.removeItem('token');
        setTimeout(() => router.push('/'), 2000);
    }
});

const logout = async () => {
    try {
        await axios.post('/logout');
    } catch (error) {
        console.error('Logout error', error);
    } finally {
        // Hapus token dan redirect ke login
        localStorage.removeItem('token');
        router.push('/');
    }
};
</script>

<template>
    <div class="dashboard-container">
        <h1>Dashboard Utama</h1>
        <div v-if="user" class="card">
            <p class="welcome-text">{{ message }}</p>
            <div class="user-info">
                <p><strong>Email:</strong> {{ user.email }}</p>
                <p><strong>Role:</strong> {{ user.role }}</p>
            </div>
            <button @click="logout" class="btn-logout">Logout</button>
        </div>
        <p v-else>{{ message }}</p>
    </div>
</template>

<style scoped>
.dashboard-container { max-width: 600px; margin: 50px auto; font-family: sans-serif; text-align: center; }
.card { border: 1px solid #ddd; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: inline-block; text-align: left; min-width: 300px; }
.welcome-text { font-size: 1.2em; color: #2c3e50; margin-bottom: 20px; text-align: center; }
.user-info { margin-bottom: 20px; background: #f9f9f9; padding: 15px; border-radius: 4px; }
.btn-logout { background-color: #e74c3c; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; width: 100%; }
.btn-logout:hover { background-color: #c0392b; }
</style>