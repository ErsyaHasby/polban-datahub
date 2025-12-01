<template>
  <div class="dashboard-layout">
    <Navbar :user="authStore.user" @logout="logout" @toggle-sidebar="toggleSidebar" />
    <div class="main-wrapper">
      <Sidebar 
        :isAdmin="authStore.isAdmin" :isParticipant="authStore.isParticipant" 
        :isOpen="isSidebarOpen"
      />
      <main class="page-content" :class="{ 'full-width': !isSidebarOpen }">
        <div class="content-container">
          <div class="welcome-header">
            <h1 class="page-title">Selamat Datang, <span class="highlight-name">{{ authStore.user?.name }}</span></h1>
            <p class="page-subtitle" v-if="authStore.isAdmin">Anda login sebagai <strong>Admin</strong>.</p>
            <p class="page-subtitle" v-else>Selamat datang di dashboard participant.</p>
          </div>

          <div class="content-body">
             </div>
        </div>
        <Footer />
      </main>
    </div>
    <ImportModal v-if="showImportModal" @close="showImportModal = false" />
  </div>
</template>

<script>
import { useAuthStore } from '../stores/auth'
import Navbar from '../components/Navbar.vue'
import Sidebar from '../components/Sidebar.vue'
import Footer from '../components/Footer.vue'
import ImportModal from '../components/ImportModal.vue'
import ExportModal from '../components/ExportModal.vue'

export default {
  name: 'Dashboard',
  components: { Navbar, Sidebar, Footer, ImportModal, ExportModal },
  setup() { return { authStore: useAuthStore() } },
  data() {
    return {
      // 1. LOGIC PENTING: Baca status dari localStorage agar sidebar tidak berubah sendiri
      isSidebarOpen: localStorage.getItem('sidebarState') === 'closed' ? false : true,
      
      showImportModal: false,
      showExportModal: false
    }
  },
  methods: {
    // 2. Toggle hanya mengubah status dan menyimpannya
    toggleSidebar() {
      this.isSidebarOpen = !this.isSidebarOpen;
      localStorage.setItem('sidebarState', this.isSidebarOpen ? 'open' : 'closed');
    },
    async logout() {
      if (confirm('Yakin ingin logout?')) {
        await this.authStore.logout()
        this.$router.push({ name: 'login' })
      }
    }
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inria+Sans:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap');

.dashboard-layout {
  font-family: 'Inria Sans', sans-serif;
  min-height: 100vh;
  background-color: #f8fafc;
  display: flex; flex-direction: column;
}

.main-wrapper {
  display: flex;
  flex: 1;
  padding-top: 90px;
  min-height: 100vh;
}

/* KONTEN KANAN */
.page-content {
  flex: 1; 
  /* Margin saat sidebar TERBUKA */
  margin-left: 280px; 
  
  display: flex;
  flex-direction: column;
  min-height: calc(100vh - 90px);
  transition: margin-left 0.3s ease-in-out; 
}

/* Margin saat sidebar TERTUTUP (MINI) */
/* INI PENTING: 90px agar tidak tertutup sidebar mini */
.page-content.full-width {
  margin-left: 90px; 
}

.content-container {
  padding: 2rem 4rem; 
  flex: 1; 
  background: #f8fafc;
}

.welcome-header { margin-bottom: 2rem; }
.welcome-header h1 { color: #1B2376; font-size: 3rem; font-weight: 700; margin-bottom: 0.5rem; }
.highlight-name { color: #F6983E; }
.page-subtitle { color: #64748b; font-size: 1.2rem; margin-top: 0.5rem; }
</style>