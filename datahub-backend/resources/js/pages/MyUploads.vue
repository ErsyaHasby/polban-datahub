<template>
  <div class="dashboard-layout">
    <Navbar :user="authStore.user" @logout="logout" @toggle-sidebar="toggleSidebar" />
    <div class="main-wrapper">
      <Sidebar :isParticipant="true" :isOpen="isSidebarOpen" @openImport="showImportModal = true" />
      <main class="page-content" :class="{ 'full-width': !isSidebarOpen }">
        <div class="content-container">
          <div class="page-header">
            <h1 class="page-title">Riwayat Upload Saya</h1>
            <p class="page-subtitle">Pantau status file yang telah Anda kirim</p>
          </div>
          <div class="table-card">
            <div v-if="loading" class="state-loading">Memuat data...</div>
            <table v-else class="custom-table">
              <thead>
                <tr>
                  <th>Nama File</th>
                  <th>Tanggal Upload</th>
                  <th>Jumlah Baris</th>
                  <th>Status</th>
                  <th>Catatan Admin</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="file in uploads" :key="file.batch_id">
                  <td class="font-bold text-dark">{{ file.filename }}</td>
                  <td>{{ formatDate(file.created_at) }}</td>
                  <td>{{ file.total_rows }} baris</td>
                  <td><span :class="['badge', statusClass(file.status)]">{{ file.status }}</span></td>
                  <td class="text-red">{{ file.admin_notes || '-' }}</td>
                </tr>
                <tr v-if="uploads.length === 0">
                  <td colspan="5" class="state-empty">Belum ada file yang diupload.</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <Footer />
      </main>
    </div>
    <ImportModal v-if="showImportModal" @close="showImportModal = false" @uploaded="fetchUploads" />
  </div>
</template>

<script>
import axios from 'axios'
import { useAuthStore } from '../stores/auth'
import Navbar from '../components/Navbar.vue'
import Sidebar from '../components/Sidebar.vue'
import Footer from '../components/Footer.vue'
import ImportModal from '../components/ImportModal.vue'

export default {
  components: { Navbar, Sidebar, Footer, ImportModal },
  setup() { return { authStore: useAuthStore() } },
  data() {
    return {
      // Logic localStorage
      isSidebarOpen: localStorage.getItem('sidebarState') === 'closed' ? false : true,
      showImportModal: false, loading: false, uploads: []
    }
  },
  mounted() { this.fetchUploads() },
  methods: {
    toggleSidebar() { 
      this.isSidebarOpen = !this.isSidebarOpen;
      localStorage.setItem('sidebarState', this.isSidebarOpen ? 'open' : 'closed');
    },
    async fetchUploads() {
      this.loading = true
      try {
        const res = await axios.get('/my-uploads', {
          headers: { Authorization: `Bearer ${this.authStore.token}` }
        });
        this.uploads = res.data.data;
      } catch (e) { console.error(e) }
      finally { this.loading = false }
    },
    statusClass(status) {
      if(status === 'approved') return 'badge-success';
      if(status === 'rejected') return 'badge-danger';
      return 'badge-warning';
    },
    formatDate(date) {
      if(!date) return '-';
      return new Date(date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour:'2-digit', minute:'2-digit' });
    },
    async logout() { await this.authStore.logout(); this.$router.push({name:'login'}); }
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inria+Sans:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap');

.dashboard-layout { display: flex; flex-direction: column; min-height: 100vh; background-color: #f3f4f6; font-family: 'Inria Sans', sans-serif; }
.main-wrapper { display: flex; flex: 1; padding-top: 90px; min-height: 100vh; }

/* FIX Layout */
.page-content { flex: 1; margin-left: 280px; display: flex; flex-direction: column; min-height: calc(100vh - 90px); transition: margin-left 0.3s ease; }
.page-content.full-width { margin-left: 90px; }
.content-container { max-width: 1200px; margin: 0 auto; flex: 1; width: 100%; padding: 2rem 3rem; }

/* Table Styles */
.page-header { margin-bottom: 2rem; }
.page-title { font-size: 1.8rem; font-weight: 700; color: #111827; margin-bottom: 0.2rem; }
.page-subtitle { color: #6b7280; font-size: 1rem; }
.table-card { background: white; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); overflow: hidden; }
.custom-table { width: 100%; border-collapse: collapse; text-align: left; }
.custom-table th { background-color: #f9fafb; color: #374151; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; }
.custom-table td { padding: 1rem 1.5rem; border-bottom: 1px solid #f3f4f6; color: #4b5563; font-size: 0.95rem; vertical-align: middle; }
.custom-table tbody tr:hover { background-color: #f9fafb; }
.font-bold { font-weight: 600; } .text-dark { color: #111827; } .text-red { color: #ef4444; font-style: italic; font-size: 0.9rem; }
.state-loading, .state-empty { padding: 3rem; text-align: center; color: #9ca3af; font-style: italic; }
.badge { display: inline-flex; align-items: center; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
.badge-success { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
.badge-danger { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
.badge-warning { background-color: #fff7ed; color: #9a3412; border: 1px solid #fed7aa; }
</style>