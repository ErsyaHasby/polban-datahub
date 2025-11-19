<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

// Sesuaikan path import axios ke file yang sudah kita pindahkan
import axios from '../axios';

const router = useRouter();
const email = ref('');
const password = ref('');
const message = ref('');
const isLoading = ref(false);

const login = async () => {
  isLoading.value = true;
  message.value = '';
  
  try {
    const res = await axios.post('/login', {
      email: email.value,
      password: password.value
    });

    // 1. Simpan token
    localStorage.setItem('token', res.data.access_token);
    
    // 2. Tampilkan pesan sukses
    message.value = 'Login berhasil! Mengalihkan...';
    
    // 3. Redirect ke dashboard
    // Kita beri sedikit delay (misal 500ms) agar user sempat membaca pesan sukses
    // Atau bisa langsung router.push('/dashboard') kalau mau instan
    setTimeout(() => {
        router.push('/dashboard');
    }, 500);

  } catch (err) {
    console.error(err);
    message.value = err.response?.data?.message || 'Login gagal. Periksa email & password.';
  } finally {
    isLoading.value = false;
  }
}
</script>

<template>
  <div class="login-container">
    <h2>Login DataHub</h2>
    <form @submit.prevent="login">
        <div class="form-group">
            <input v-model="email" type="email" placeholder="Email" required />
        </div>
        <div class="form-group">
            <input v-model="password" type="password" placeholder="Password" required />
        </div>
        <button type="submit" :disabled="isLoading">
            {{ isLoading ? 'Memproses...' : 'Login' }}
        </button>
    </form>
    <p v-if="message" class="message" :class="{ error: !message.includes('berhasil') }">
        {{ message }}
    </p>
  </div>
</template>

<style scoped>
.login-container { max-width: 350px; margin: 80px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; text-align: center; background: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
.form-group { margin-bottom: 15px; }
input { width: 100%; padding: 10px; margin: 5px 0; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
button { width: 100%; padding: 10px; background-color: #42b983; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; transition: background-color 0.3s; }
button:hover:not(:disabled) { background-color: #3aa876; }
button:disabled { background-color: #a0dcb9; cursor: not-allowed; }
.message { margin-top: 15px; font-size: 14px; color: green; font-weight: bold; }
.message.error { color: red; }
</style>