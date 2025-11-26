<template>
  <div class="login-page" @mousemove="handleMouseMove">
    
    <div class="background-shapes">
      
      <div class="blob-container right-container" :style="containerRightStyle">
        <div class="blob shape-right"></div>
        <div class="blob shape-right-layer"></div>
      </div>
      
      <div class="blob-container left-container" :style="containerLeftStyle">
        <div class="blob shape-left"></div>
        <div class="blob shape-left-layer"></div>
      </div>

    </div>

    <div class="login-content" :style="contentParallax">
      
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
      email: '',
      password: '',
      loading: false,
      errorMessage: '',
      showPassword: false,
      mouseX: 0,
      mouseY: 0,
    }
  },
  computed: {
    // Parallax untuk Container Blob Kanan
    containerRightStyle() {
      return {
        transform: `translate(${this.mouseX * -0.03}px, ${this.mouseY * -0.03}px)`
      }
    },
    // Parallax untuk Container Blob Kiri
    containerLeftStyle() {
      return {
        transform: `translate(${this.mouseX * 0.05}px, ${this.mouseY * 0.05}px)`
      }
    },
    // Parallax Content
    contentParallax() {
      return {
        transform: `translate(${this.mouseX * -0.01}px, ${this.mouseY * -0.01}px)`
      }
    }
  },
  methods: {
    async login() {
      this.loading = true
      this.errorMessage = ''
      
      try {
        const authStore = useAuthStore()
        const result = await authStore.login(this.email, this.password)
        
        if (result.success) {
          this.$router.push({ name: 'dashboard' }) 
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
    handleMouseMove(e) {
      this.mouseX = e.clientX - window.innerWidth / 2
      this.mouseY = e.clientY - window.innerHeight / 2
    }
  }
}
</script>

<style scoped>
/* =========================================
   1. VARIABLES & LAYOUT UTAMA
   ========================================= */
* {
  box-sizing: border-box;
}

.login-page {
  /* Background Utama: Gradient Biru Polban */
  background: linear-gradient(135deg, #2148C0 0%, #172a75 100%);
  height: 100vh;
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  overflow: hidden; 
  font-family: 'Poppins', sans-serif;
  color: #fff;
}

/* =========================================
   2. BACKGROUND LIQUID BLOBS (Animasi Morphing)
   ========================================= */
.background-shapes {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  overflow: hidden; 
  z-index: 0;
  pointer-events: none;
}

.blob-container {
  position: absolute;
  transition: transform 0.2s ease-out; 
}

.right-container {
  top: -10%;
  right: -10%;
  width: 60vw;
  height: 60vw;
}

.left-container {
  bottom: -15%;
  left: -15%;
  width: 50vw;
  height: 50vw;
}

/* Base Blob Style */
.blob {
  position: absolute;
  width: 100%;
  height: 100%;
  border-radius: 50%;
  /* Animasi "Wobble" Morphing */
  animation: wobble 10s ease-in-out infinite alternate;
}

/* Shape Kanan (Warna Utama) */
.shape-right {
  background: linear-gradient(45deg, #1a359c, #2a52d6);
  opacity: 0.8;
  z-index: 2;
}

/* Shape Kanan (Layer Belakang) */
.shape-right-layer {
  background: #1a359c;
  opacity: 0.3;
  transform: scale(1.1) rotate(20deg);
  animation: wobble 15s ease-in-out infinite alternate-reverse;
  z-index: 1;
}

/* Shape Kiri (Warna Utama) */
.shape-left {
  background: linear-gradient(to right, #152c7a, #1f40b0);
  opacity: 0.9;
  z-index: 2;
  animation: wobble 12s ease-in-out infinite alternate;
}

/* Shape Kiri (Layer Belakang) */
.shape-left-layer {
  background: #152c7a;
  opacity: 0.4;
  transform: scale(1.2) rotate(-10deg);
  animation: wobble 18s ease-in-out infinite alternate-reverse;
  z-index: 1;
}

/* Keyframes untuk Efek Liquid/Air */
@keyframes wobble {
  0% {
    border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
    transform: scale(1);
  }
  25% {
    border-radius: 45% 55% 59% 41% / 47% 39% 61% 53%;
  }
  50% {
    border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%;
    transform: scale(1.02) rotate(5deg);
  }
  75% {
    border-radius: 65% 35% 30% 70% / 35% 60% 40% 60%;
  }
  100% {
    border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
    transform: scale(1);
  }
}

/* =========================================
   3. CONTENT CONTAINER (NO BOX / TRANSPARAN)
   ========================================= */
.login-content {
  position: relative;
  z-index: 10; 
  width: 100%;
  max-width: 400px;
  padding: 1rem;
  text-align: center;
  background: transparent;
  box-shadow: none;
  border: none;
  backdrop-filter: none;
}

.login-header {
  margin-bottom: 2rem;
}

.logo-image {
  height: 90px;
  margin-bottom: 0.5rem;
  display: block;
  margin-left: auto;
  margin-right: auto;
  filter: drop-shadow(0 4px 6px rgba(0,0,0,0.2));
}

.app-title {
  font-size: 1.8rem;
  font-weight: 700;
  margin: 0;
  letter-spacing: 1px;
  text-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

/* =========================================
   4. FORM INPUTS & BUTTONS
   ========================================= */
.login-form {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.input-group {
  position: relative;
  display: flex;
  align-items: center;
  background-color: #383547;
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 50px; 
  padding: 0.2rem 1.2rem;
  box-shadow: 0 4px 15px rgba(0,0,0,0.2); 
  transition: transform 0.3s, border-color 0.3s;
}

.input-group:focus-within {
  transform: scale(1.02);
  border-color: #4facfe;
  background-color: #403d50;
}

.input-icon svg {
  width: 20px;
  height: 20px;
  color: #a0a0a0;
  margin-right: 12px;
}

.form-input {
  flex: 1;
  background: transparent;
  border: none;
  color: #ffffff;
  padding: 1rem 0;
  font-size: 1rem;
  outline: none;
}

.form-input::placeholder {
  color: #888;
  font-size: 0.9rem;
}

.toggle-password {
  background: none;
  border: none;
  cursor: pointer;
  color: #a0a0a0;
  display: flex;
  align-items: center;
  padding: 5px;
}
.toggle-password:hover { color: #fff; }

.btn-login {
  width: 100%;
  background-color: #ffffff;
  color: #21308F; 
  font-weight: 800;
  padding: 1rem;
  border: none;
  border-radius: 50px;
  font-size: 1.1rem;
  cursor: pointer;
  letter-spacing: 1px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.3);
  transition: transform 0.2s, background-color 0.2s;
  margin-top: 1rem;
}

.btn-login:hover {
  transform: translateY(-2px);
  background-color: #f0f0f0;
}

.btn-login:disabled {
  background-color: #ccc;
  cursor: not-allowed;
  transform: none;
}

.forgot-pass-container {
  text-align: center; 
  margin-top: 1rem;
}

.forgot-link {
  color: rgba(255, 255, 255, 0.8);
  font-size: 0.9rem;
  text-decoration: none;
  text-shadow: 0 1px 2px rgba(0,0,0,0.3);
}

.forgot-link:hover {
  color: #ffffff;
  text-decoration: underline;
}

.error-alert {
  background: rgba(220, 38, 38, 0.8);
  border: none;
  color: #fff;
  padding: 0.8rem;
  border-radius: 12px;
  font-size: 0.9rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}

/* =========================================
   5. BUG FIXES (AUTOFILL & DOUBLE EYE)
   ========================================= */
.form-input:-webkit-autofill,
.form-input:-webkit-autofill:hover, 
.form-input:-webkit-autofill:focus, 
.form-input:-webkit-autofill:active {
    -webkit-box-shadow: 0 0 0 30px #383547 inset !important;
    -webkit-text-fill-color: white !important;
    transition: background-color 5000s ease-in-out 0s;
}
input[type="password"]::-ms-reveal,
input[type="password"]::-ms-clear {
    display: none;
}
</style>