<template>
  <nav class="navbar">
    
    <div class="nav-left">
      <button class="nav-box btn-menu" @click="$emit('toggle-sidebar')">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
      </button>
      
      <div class="nav-box logo-box">
        <img :src="'/images/logo.png'" alt="Logo" class="nav-logo" />
      </div>
      <h2 class="brand-text">Polban DataHub</h2>
    </div>

    <div class="nav-right">
      <button class="nav-box btn-moon">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
      </button>

      <div class="nav-box user-profile" @click="toggleDropdown">
        <img :src="'https://ui-avatars.com/api/?name=' + user?.name + '&background=random'" alt="User" class="avatar" />
        <span class="user-name">{{ user?.name || 'Admin' }}</span>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
        
        <div v-if="showDropdown" class="dropdown-menu">
          <button @click="$emit('logout')" class="dropdown-item text-red">
            Logout
          </button>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'Navbar',
  props: {
    user: Object
  },
  data() {
    return {
      showDropdown: false
    }
  },
  methods: {
    toggleDropdown() {
      this.showDropdown = !this.showDropdown;
    }
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inria+Sans:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap');

.navbar {
  font-family: 'Inria Sans', sans-serif;
  background-color: #1B2376; 
  color: white;
  height: 90px; /* Sedikit lebih tinggi untuk menampung box */
  
  /* REVISI: Padding diperbesar agar elemen geser ke tengah (tidak mepet pinggir) */
  padding: 0 1rem; 
  
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 50;
  box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.nav-left, .nav-right {
  display: flex;
  align-items: center;
  gap: 1rem; /* Jarak antar elemen */
}

/* === STYLE UMUM UNTUK SEMUA KOTAK (BOX) === */
.nav-box {
  background-color: #3A4DC6; /* Warna sesuai request */
  border-radius: 15px;      /* Corner radius */
  height: 50px;             /* Tinggi seragam untuk semua box */
  display: flex;
  align-items: center;
  justify-content: center;
  border: none;
  color: white;
  transition: background 0.3s, transform 0.2s;
  box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.nav-box:hover {
  background-color: #4c6ef5; /* Efek hover sedikit lebih terang */
}

/* 1. Tombol Hamburger */
.btn-menu {
  width: 50px; /* Kotak persegi */
  cursor: pointer;
}

/* 2. Logo Box */
.logo-box {
  padding: 0 12px; /* Padding kiri kanan agar logo enak dilihat */
  width: auto;
}
.nav-logo {
  height: 30px;
  width: auto;
}
.brand-text {
  font-size: 1.5rem;
  font-weight: 700;
  margin: 0 0 0 0.5rem;
}

/* 3. Bulan Sabit (Square Box) */
.btn-moon {
  width: 50px; /* Lebar sama dengan tinggi agar kotak persegi */
  cursor: pointer;
}

/* 4. User Profile (Rectangular Box) */
.user-profile {
  padding: 0 1.2rem 0 0.5rem; /* Padding internal */
  gap: 0.8rem;
  cursor: pointer;
  position: relative;
  min-width: 140px; /* Lebar minimum agar seimbang */
}

.avatar {
  width: 36px;
  height: 36px;
  border-radius: 10px;
}

.user-name {
  font-size: 1rem;
  font-weight: 700;
}

/* Dropdown */
.dropdown-menu {
  position: absolute;
  top: 130%; /* Sedikit di bawah box */
  right: 0;
  background: white;
  border-radius: 15px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  width: 160px;
  overflow: hidden;
  padding: 0.5rem 0;
  z-index: 100;
}

.dropdown-item {
  display: block;
  width: 100%;
  text-align: left;
  padding: 0.8rem 1.2rem;
  border: none;
  background: none;
  cursor: pointer;
  font-family: 'Inria Sans', sans-serif;
  font-weight: 700;
  color: #333;
}

.dropdown-item:hover { background: #f1f5f9; }
.text-red { color: #dc2626; }
</style>