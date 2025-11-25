<template>
  <div class="login-page" @mousemove="handleMouseMove">
    
    <div class="bg-shape shape-1" :style="shapeStyle1"></div>
    <div class="bg-shape shape-2" :style="shapeStyle2"></div>

    <div class="login-container">
      
      <div class="login-header">
        <img :src="'/images/logo.png'" alt="Logo Polban" class="logo-image" />
        <h1 class="app-title">Polban DataHub</h1>
      </div>

      <form @submit.prevent="login" class="login-form">
        
        <div v-if="errorMessage" class="error-alert">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
          <span>{{ errorMessage }}</span>
        </div>

        <div class="input-group">
          <div class="input-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
          </div>
          <input
            v-model="email"
            type="email"
            placeholder="USERNAME / EMAIL"
            required
            class="form-input"
          />
        </div>

        <div class="input-group">
          <div class="input-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
          </div>
          <input
            v-model="password"
            :type="showPassword ? 'text' : 'password'"
            placeholder="PASSWORD"
            required
            class="form-input"
          />
          <button type="button" class="toggle-password" @click="showPassword = !showPassword">
            <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
          </button>
        </div>

        <button type="submit" class="btn-login" :disabled="loading">
          {{ loading ? 'PROCESSING...' : 'LOGIN' }}
        </button>
        
        <div class="forgot-pass-container">
          <a href="#" class="forgot-link">Forgot password?</a>
        </div>

      </form>
    </div>
  </div>
</template>

<script>
import { useAuthStore } from '../stores/auth'

export default {
  name: 'Login',
  data() {
    return {
      // --- Data Logika Auth ---
      email: '',
      password: '',
      loading: false,
      errorMessage: '',
      
      // --- Data Tampilan UI ---
      showPassword: false,
      mouseX: 0,
      mouseY: 0,
    }
  },
  computed: {
    // Menghitung posisi background shape berdasarkan posisi mouse (Parallax)
    shapeStyle1() {
      return {
        transform: `translate(${this.mouseX * -0.05}px, ${this.mouseY * -0.05}px)`
      }
    },
    shapeStyle2() {
      return {
        transform: `translate(${this.mouseX * 0.08}px, ${this.mouseY * 0.08}px)`
      }
    }
  },
  methods: {
    // --- Logic 1: Auth (TIDAK BERUBAH DARI KODE ASLI) ---
    async login() {
      this.loading = true
      this.errorMessage = ''
      
      try {
        const authStore = useAuthStore()
        const result = await authStore.login(this.email, this.password)
        
        if (result.success) {
          this.$router.push({ name: 'dashboard' }) // Redirect tetap sama
        } else {
          this.errorMessage = result.message
        }
      } catch (err) {
        console.error('Login error:', err)
        this.errorMessage = 'Terjadi kesalahan. Silakan coba lagi.'
      } finally {
        this.loading = false
      }
    },

    // --- Logic 2: UI Interaction ---
    handleMouseMove(e) {
      // Mengambil koordinat mouse relatif terhadap tengah layar
      this.mouseX = e.clientX - window.innerWidth / 2
      this.mouseY = e.clientY - window.innerHeight / 2
    }
  }
}
</script>

<style scoped>
/* =========================================
   1. VARIABLES & RESET
   ========================================= */
* {
  box-sizing: border-box;
}

.login-page {
  /* Warna Background Utama: Biru Muda (#2148C0) */
  background-color: #2148C0; 
  height: 100vh;
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  overflow: hidden; /* Mencegah scrollbar saat animasi bergerak */
  font-family: 'Poppins', sans-serif;
  color: #fff;
}

/* =========================================
   2. BACKGROUND ANIMATION (PARALLAX)
   ========================================= */
.bg-shape {
  position: absolute;
  background-color: #21308F; /* Warna Biru Tua untuk Shape */
  border-radius: 50%;
  z-index: 1; /* Di bawah form login */
  transition: transform 0.1s ease-out; /* Smooth movement */
  opacity: 0.6;
}

.shape-1 {
  width: 600px;
  height: 600px;
  bottom: -150px;
  left: -150px;
}

.shape-2 {
  width: 400px;
  height: 400px;
  top: -50px;
  right: -100px;
}

/* =========================================
   3. LOGIN CONTAINER
   ========================================= */
.login-container {
  position: relative;
  z-index: 10; /* Di atas background shape */
  width: 100%;
  max-width: 400px;
  padding: 2rem;
  text-align: center;
  /* Glassmorphism effect opsional, atau transparan */
  background: rgba(255, 255, 255, 0.05); /* Sedikit transparan */
  backdrop-filter: blur(10px);
  border-radius: 20px;
  box-shadow: 0 15px 35px rgba(0,0,0,0.2);
}

/* Header Logo */
.login-header {
  margin-bottom: 2.5rem;
}

.logo-image {
  height: 80px; /* Sesuaikan ukuran logo */
  margin-bottom: 1rem;
  display: block;
  margin-left: auto;
  margin-right: auto;
}

.app-title {
  font-size: 1.5rem;
  font-weight: 700;
  margin: 0;
  letter-spacing: 0.5px;
}

/* =========================================
   4. FORM ELEMENTS
   ========================================= */
.login-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

/* Input Wrapper */
.input-group {
  position: relative;
  display: flex;
  align-items: center;
  /* Warna Box Input: #383547 */
  background-color: #383547;
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 8px; /* Rounded corners */
  padding: 0.2rem 1rem;
  transition: all 0.3s ease;
}

.input-group:focus-within {
  border-color: #4facfe; /* Highlight biru muda saat fokus */
  box-shadow: 0 0 8px rgba(79, 172, 254, 0.4);
}

/* Icon di kiri */
.input-icon svg {
  width: 20px;
  height: 20px;
  color: #a0a0a0;
  margin-right: 12px;
}

/* Input Field */
.form-input {
  flex: 1;
  background: transparent;
  border: none;
  color: #ffffff;
  padding: 0.8rem 0;
  font-size: 0.95rem;
  outline: none;
}

.form-input::placeholder {
  color: #888;
  font-size: 0.85rem;
  letter-spacing: 1px;
}

/* Toggle Password Button */
.toggle-password {
  background: none;
  border: none;
  cursor: pointer;
  color: #a0a0a0;
  padding: 0;
  display: flex;
  align-items: center;
}

.toggle-password:hover {
  color: #fff;
}

/* Error Alert */
.error-alert {
  background: rgba(220, 38, 38, 0.2);
  border: 1px solid #ef4444;
  color: #ffcccc;
  padding: 0.8rem;
  border-radius: 8px;
  font-size: 0.85rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

/* =========================================
   5. BUTTON & FOOTER
   ========================================= */
.btn-login {
  width: 100%;
  /* Tombol Putih dengan teks Biru sesuai desain DataHub */
  background-color: #ffffff;
  color: #2148C0;
  font-weight: 700;
  padding: 0.9rem;
  border: none;
  border-radius: 50px; /* Pill shape */
  font-size: 1rem;
  cursor: pointer;
  letter-spacing: 1px;
  transition: transform 0.2s, box-shadow 0.2s;
  margin-top: 0.5rem;
}

.btn-login:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(255, 255, 255, 0.3);
}

.btn-login:disabled {
  background-color: #ccc;
  cursor: not-allowed;
  transform: none;
}

.forgot-pass-container {
  text-align: right;
}

.forgot-link {
  color: rgba(255, 255, 255, 0.7);
  font-size: 0.85rem;
  text-decoration: none;
  transition: color 0.3s;
}

.forgot-link:hover {
  color: #ffffff;
  text-decoration: underline;
}
</style>