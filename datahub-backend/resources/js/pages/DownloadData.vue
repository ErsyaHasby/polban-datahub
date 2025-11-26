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
      />

      <main class="page-content" :class="{ 'full-width': !isSidebarOpen }">
        <div class="content-container">
          
          <div class="page-header">
            <div>
              <h1 class="page-title">Download Data</h1>
              <p class="page-subtitle">Unduh rekapitulasi data mahasiswa dalam format Excel</p>
            </div>
            <div class="breadcrumb">
              <span>DataHub</span> / <span class="active">Download</span>
            </div>
          </div>

          <div class="download-card">
            
            <div class="card-left">
              <div class="icon-illustration">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
              </div>
              <h3>Export Database</h3>
              <p>Masukkan kata kunci untuk memfilter data, lalu unduh dalam format .xlsx</p>
            </div>

            <div class="card-right">
              <form @submit.prevent="downloadData" class="filter-form">
                
                <div class="form-group">
                  <label>Filter Pencarian</label>
                  <div class="single-search-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="input-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input 
                      type="text" 
                      v-model="searchQuery" 
                      placeholder="Cari data" 
                      class="form-input-single"
                    >
                  </div>
                </div>

                <button type="submit" class="btn-download" :disabled="loading">
                  <span v-if="loading" class="spinner"></span>
                  <span v-else>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    Unduh Data Excel
                  </span>
                </button>

              </form>
            </div>

          </div>

        </div>

        <Footer />
      </main>

    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { useAuthStore } from '../stores/auth'
import Navbar from '../components/Navbar.vue'
import Sidebar from '../components/Sidebar.vue'
import Footer from '../components/Footer.vue'

export default {
  name: 'DownloadData',
  components: { Navbar, Sidebar, Footer },
  data() {
    return {
      isSidebarOpen: true,
      loading: false,
      searchQuery: '' // Single search variable
    }
  },
  setup() {
    const authStore = useAuthStore()
    return { authStore }
  },
  methods: {
    toggleSidebar() { this.isSidebarOpen = !this.isSidebarOpen },
    async downloadData() {
      this.loading = true
      try {
        // Note: Mengirim 'search' sebagai parameter umum.
        // Backend perlu menyesuaikan jika ingin filter pintar, atau kita bisa mapping di sini.
        const response = await axios.get('/api/export-data', {
          params: { search: this.searchQuery }, 
          responseType: 'blob',
          headers: { 'Authorization': `Bearer ${this.authStore.token}` }
        })

        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement('a')
        link.href = url
        link.setAttribute('download', `Data_Mahasiswa_${new Date().getTime()}.xlsx`)
        document.body.appendChild(link)
        link.click()
        document.body.removeChild(link)

      } catch (error) {
        alert('Gagal mengunduh data. Silakan coba lagi.')
        console.error(error)
      } finally {
        this.loading = false
      }
    },
    async logout() {
      if (confirm('Logout?')) {
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
}
.main-wrapper { display: flex; padding-top: 90px; }
.page-content {
  flex: 1; margin-left: 280px; display: flex; flex-direction: column;
  min-height: calc(100vh - 90px); transition: margin-left 0.3s ease-in-out;
}
.page-content.full-width { margin-left: 0; }
.content-container { padding: 2rem 4rem; flex: 1; }

/* Header */
.page-header { display: flex; justify-content: space-between; margin-bottom: 2rem; }
.page-title { color: #1B2376; font-size: 2rem; font-weight: 700; margin: 0; }
.page-subtitle { color: #64748b; margin-top: 0.2rem; font-size: 1rem; }
.breadcrumb { font-size: 0.9rem; color: #94a3b8; font-weight: 600; }
.breadcrumb .active { color: #1B2376; }

/* Download Card */
.download-card {
  background: white; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.05);
  display: flex; overflow: hidden; min-height: 400px;
}
.card-left {
  flex: 1; background: linear-gradient(135deg, #1B2376 0%, #3A4DC6 100%);
  color: white; padding: 3rem; display: flex; flex-direction: column;
  justify-content: center; align-items: center; text-align: center;
}
.icon-illustration svg { width: 120px; height: 120px; opacity: 0.9; margin-bottom: 1.5rem; }
.card-left h3 { font-size: 1.8rem; margin-bottom: 1rem; font-weight: 700; }
.card-left p { font-size: 1rem; line-height: 1.6; opacity: 0.9; max-width: 80%; }

.card-right {
  flex: 1.5; padding: 3rem 4rem; display: flex; flex-direction: column;
  justify-content: center;
}
.filter-form { display: flex; flex-direction: column; gap: 1.5rem; }
.form-group { display: flex; flex-direction: column; gap: 0.5rem; }
.form-group label { color: #1B2376; font-weight: 700; font-size: 1rem; }

/* Single Input Style */
.single-search-wrapper {
  display: flex; align-items: center; position: relative;
}
.input-icon {
  position: absolute; left: 1rem; color: #94a3b8; pointer-events: none;
}
.form-input-single {
  width: 100%; padding: 1rem 1rem 1rem 3rem; /* Padding kiri besar untuk icon */
  border: 2px solid #e2e8f0; border-radius: 10px; font-family: inherit;
  font-size: 1.1rem; transition: border-color 0.3s; outline: none;
}
.form-input-single:focus { border-color: #F6983E; }

.btn-download {
  margin-top: 1rem; background-color: #F6983E; color: white; border: none;
  padding: 1rem; border-radius: 10px; font-weight: 700; font-size: 1.1rem;
  cursor: pointer; display: flex; align-items: center; justify-content: center;
  gap: 0.8rem; transition: transform 0.2s, box-shadow 0.2s;
}
.btn-download:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(246, 152, 62, 0.4); }
.btn-download:disabled { background-color: #cbd5e1; cursor: not-allowed; transform: none; box-shadow: none; }
.spinner {
  border: 3px solid rgba(255,255,255,0.3); border-radius: 50%;
  border-top: 3px solid white; width: 20px; height: 20px; animation: spin 1s linear infinite;
}
@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
</style>