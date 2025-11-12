<script setup>
import { ref } from 'vue'
import axios from 'axios'

const email = ref('')
const password = ref('')
const message = ref('')

const login = async () => {
  try {
    const res = await axios.post('http://localhost:8000/api/login', {
      email: email.value,
      password: password.value
    })
    localStorage.setItem('token', res.data.access_token)
    message.value = 'Login berhasil!'
  } catch (err) {
    message.value = err.response?.data?.message || 'Login gagal'
  }
}
</script>

<template>
  <div>
    <h2>Login</h2>
    <input v-model="email" placeholder="Email" />
    <input v-model="password" placeholder="Password" type="password" />
    <button @click="login">Login</button>
    <p>{{ message }}</p>
  </div>
</template>
