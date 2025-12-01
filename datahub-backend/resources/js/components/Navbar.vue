<template>
  <nav class="navbar">
    
    <div class="nav-left">
      <button class="nav-btn-icon" @click="$emit('toggle-sidebar')">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
      </button>
      
      <router-link to="/" class="brand-link">
        <div class="logo-wrapper">
          <img :src="'/images/logo.png'" alt="Logo" class="nav-logo" />
        </div>
        <h2 class="brand-text">Polban <span>DataHub</span></h2>
      </router-link>
    </div>

    <div class="nav-right">
      
      <button class="nav-btn-icon theme-btn" @click="toggleTheme" title="Ganti Tema">
        <svg v-if="!isDarkMode" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
      </button>

      <div class="user-pill" @click="toggleDropdown">
        <img :src="'https://ui-avatars.com/api/?name=' + user?.name + '&background=random'" alt="User" class="avatar" />
        <span class="user-name">{{ user?.name || 'User' }}</span>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
        
        <transition name="fade">
          <div v-if="showDropdown" class="dropdown-menu">
            <button @click="$emit('logout')" class="dropdown-item text-red">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
              Logout
            </button>
          </div>
        </transition>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'Navbar',
  props: { user: Object },
  data() {
    return {
      showDropdown: false,
      // Cek preferensi tema dari localStorage
      isDarkMode: localStorage.getItem('theme') === 'dark'
    }
  },
  methods: {
    toggleDropdown() { this.showDropdown = !this.showDropdown; },
    toggleTheme() {
      this.isDarkMode = !this.isDarkMode;
      // Logika ganti tema (biasanya menambah class 'dark' ke html tag)
      if (this.isDarkMode) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
      } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
      }
    }
  },
  mounted() {
    // Terapkan tema saat load
    if (this.isDarkMode) {
      document.documentElement.classList.add('dark');
    }
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inria+Sans:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap');

.navbar {
  font-family: 'Inria Sans', sans-serif;
  /* GLOSSY BACKGROUND */
  background: linear-gradient(90deg, #0f172a 0%, #1e3a8a 100%);
  color: white;
  height: 90px;
  padding: 0 1.2rem; 
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: fixed; top: 0; left: 0; width: 100%; z-index: 50;
  box-shadow: 0 4px 30px rgba(0, 0, 0, 0.15);
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.nav-left, .nav-right { display: flex; align-items: center; gap: 1.5rem; }

/* === TOMBOL IKON KOTAK (Hamburger & Dark Mode) === */
/* Style ini dipakai bersamaan agar seragam */
.nav-btn-icon {
  background: rgba(255, 255, 255, 0.1); /* Efek Kaca */
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 50px;
  width: 45px; height: 45px; /* Kotak Presisi */
  display: flex; align-items: center; justify-content: center;
  color: white; cursor: pointer; transition: all 0.3s ease;
}
.nav-btn-icon:hover { background: rgba(255, 255, 255, 0.2); transform: scale(1.05); }

/* Khusus tombol tema: mungkin mau animasi putar */
.theme-btn svg { transition: transform 0.5s ease; }
.theme-btn:hover svg { transform: rotate(20deg); }

/* Logo Area */
.brand-link {
  display: flex; align-items: center; gap: 1rem;
  text-decoration: none; color: white; transition: opacity 0.2s;
}
.brand-link:hover { opacity: 0.9; }

.logo-wrapper {
  background: white; padding: 5px; border-radius: 50px;
  box-shadow: 0 0 15px rgba(255, 255, 255, 0.2);
}
.nav-logo { height: 32px; width: auto; display: block; }

.brand-text { font-size: 1.6rem; font-weight: 700; margin: 0; letter-spacing: 0.5px; }
.brand-text span { color: #FF914D; /* Oranye */ }

/* User Profile Pill */
.user-pill {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  padding: 6px 16px 6px 6px;
  border-radius: 50px;
  display: flex; align-items: center; gap: 10px;
  cursor: pointer; transition: all 0.3s ease;
  position: relative;
}
.user-pill:hover { background: rgba(255, 255, 255, 0.2); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }

.avatar { width: 38px; height: 38px; border-radius: 50%; border: 2px solid rgba(255,255,255,0.5); }
.user-name { font-weight: 600; font-size: 0.95rem; }

/* Dropdown */
.dropdown-menu {
  position: absolute; top: 120%; right: 0;
  background: white; border-radius: 12px;
  box-shadow: 0 10px 40px rgba(0,0,0,0.2);
  width: 180px; overflow: hidden; padding: 5px;
  animation: slideDown 0.2s ease-out;
}
@keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

.dropdown-item {
  display: flex; align-items: center; gap: 10px; width: 100%;
  padding: 10px 15px; border: none; background: transparent;
  cursor: pointer; font-family: inherit; font-weight: 600; font-size: 0.9rem;
  border-radius: 50px; transition: background 0.2s; color: #333;
}
.dropdown-item:hover { background: #f1f5f9; }
.text-red { color: #ef4444; }
</style>