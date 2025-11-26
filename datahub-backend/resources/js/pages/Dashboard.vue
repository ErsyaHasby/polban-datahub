<template>
  <div class="dashboard-layout">
    
    <Navbar 
      :user="authStore.user" 
      @logout="logout" 
      @toggle-sidebar="toggleSidebar" 
    />

    <div class="main-wrapper">
      
      <Sidebar 
        :isAdmin="authStore.isAdmin"
        :isParticipant="authStore.isParticipant"
        :isOpen="isSidebarOpen"
        @openImport="showImportModal = true"
        @openExport="showExportModal = true"
      />

      <main class="page-content" :class="{ 'full-width': !isSidebarOpen }">
        <div class="content-container">
          
          <div class="welcome-header">
            <h1>Selamat Datang, <span class="highlight-name">{{ authStore.user?.name }}!</span></h1>
            <p v-if="authStore.isAdmin">Kelola data import dan monitor aktivitas sistem di sini.</p>
            <p v-else>Import dan export data mahasiswa dengan mudah.</p>
          </div>

          <div class="content-body">
             </div>

        </div>

        <Footer />
      </main>

    </div>

    <ImportModal v-if="showImportModal" @close="showImportModal = false" />
    <ExportModal v-if="showExportModal" @close="showExportModal = false" />
  
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
  components: {
    Navbar,
    Sidebar,
    Footer,
    ImportModal,
    ExportModal
  },
  data() {
    return {
      showImportModal: false,
      showExportModal: false,
      isSidebarOpen: true 
    }
  },
  setup() {
    const authStore = useAuthStore()
    return { authStore }
  },
  methods: {
    async logout() {
      if (confirm('Yakin ingin logout?')) {
        await this.authStore.logout()
        this.$router.push({ name: 'login' })
      }
    },
    toggleSidebar() {
      this.isSidebarOpen = !this.isSidebarOpen;
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
}

.main-wrapper {
  display: flex;
  padding-top: 90px; /* Disesuaikan dengan tinggi Navbar baru (90px) */
}

.page-content {
  flex: 1; 
  margin-left: 280px; 
  display: flex;
  flex-direction: column;
  min-height: calc(100vh - 90px);
  transition: margin-left 0.3s ease-in-out; 
}

.page-content.full-width {
  margin-left: 0;
}

.content-container {
  /* REVISI PADDING: */
  /* padding-top dikurangi jadi 1rem agar teks naik mendekati navbar */
  /* padding kiri-kanan tetap 4rem agar sejajar dengan navbar */
  padding: 1rem 4rem 3rem 4rem; 
  flex: 1; 
  background: white; 
}

.welcome-header {
  margin-top: 0; 
  margin-bottom: 1rem;
}

.welcome-header h1 {
  color: #1B2376;
  font-size: 3.5rem; 
  font-weight: 700;
  margin-bottom: 0rem;
  line-height: 1.2;
}

.highlight-name {
  color: #F6983E; 
}

.welcome-header p {
  color: #64748b;
  font-size: 1.3rem; 
  font-weight: 300;
  margin-top: 0.5rem;
}
</style>