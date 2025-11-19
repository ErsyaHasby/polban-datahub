// File: resources/js/app.js

import './bootstrap';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router/index.js'; // <--- Tambahkan ini (import file yang baru kamu buat)

const app = createApp(App);

app.use(router); // <--- Tambahkan ini (aktifkan router)
app.mount('#app');