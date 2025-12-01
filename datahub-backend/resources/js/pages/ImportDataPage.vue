<template>
  <div class="dashboard-layout">
    <Navbar :user="authStore.user" @logout="logout" @toggle-sidebar="toggleSidebar" />
    <div class="main-wrapper">
      <Sidebar :isParticipant="authStore.isParticipant" :isOpen="isSidebarOpen" />
      <main class="page-content" :class="{ 'full-width': !isSidebarOpen }">
        <div class="content-container">
          <div class="page-header">
            <div>
              <h1 class="page-title">Import Data Mahasiswa</h1>
              <p class="page-subtitle">Unggah file CSV atau Excel untuk memasukkan data baru ke sistem.</p>
            </div>
            <div class="breadcrumb"><span>DataHub</span> / <span class="active">Import</span></div>
          </div>
          <div class="import-card">
            <div v-if="!file && !successMessage" class="upload-area" @dragover.prevent @drop.prevent="handleDrop" @click="$refs.fileInput.click()">
              <input ref="fileInput" type="file" accept=".csv,.xlsx,.xls" @change="handleFileSelect" style="display: none" />
              <div class="icon-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
              </div>
              <h3>Drag & Drop file di sini</h3>
              <p class="text-muted">atau klik untuk memilih file dari komputer</p>
              <p class="file-hint">Format: CSV, XLSX, XLS (Max: 10MB)</p>
            </div>
            <div v-else-if="file && !successMessage" class="file-review-area">
              <div class="file-info-card">
                <div class="file-icon">📄</div>
                <div class="file-details"><h4>{{ file.name }}</h4><span>{{ formatFileSize(file.size) }}</span></div>
                <button @click="removeFile" class="btn-remove" title="Batalkan">✕</button>
              </div>
              <div v-if="errorMessage" class="alert-box error">⚠️ {{ errorMessage }}</div>
              <div class="action-buttons">
                <button @click="removeFile" class="btn-secondary">Ganti File</button>
                <button @click="uploadFile" class="btn-primary" :disabled="uploading">
                  <span v-if="uploading" class="spinner-small"></span> {{ uploading ? 'Mengunggah...' : 'Upload Sekarang' }}
                </button>
              </div>
            </div>
            <div v-else class="success-area">
              <div class="success-icon">✅</div>
              <h3>Upload Berhasil!</h3>
              <p>{{ successMessage }}</p>
              <div class="action-buttons">
                <button @click="resetForm" class="btn-secondary">Upload Lagi</button>
                <router-link to="/my-uploads" class="btn-primary">Lihat Riwayat</router-link>
              </div>
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
  name: 'ImportDataPage',
  components: { Navbar, Sidebar, Footer },
  setup() { return { authStore: useAuthStore() } },
  data() {
    return {
      // Logic localStorage
      isSidebarOpen: localStorage.getItem('sidebarState') === 'closed' ? false : true,
      file: null, uploading: false, errorMessage: '', successMessage: ''
    }
  },
  methods: {
    toggleSidebar() { 
      this.isSidebarOpen = !this.isSidebarOpen;
      localStorage.setItem('sidebarState', this.isSidebarOpen ? 'open' : 'closed');
    },
    handleFileSelect(event) { this.validateFile(event.target.files[0]) },
    handleDrop(event) { this.validateFile(event.dataTransfer.files[0]) },
    validateFile(file) {
      this.errorMessage = ''; if (!file) return;
      const allowedTypes = ['text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
      if (!allowedTypes.includes(file.type) && !file.name.match(/\.(csv|xlsx|xls)$/)) { this.errorMessage = 'Format file tidak valid.'; return; }
      if (file.size > 10 * 1024 * 1024) { this.errorMessage = 'Ukuran file terlalu besar (Max 10MB).'; return; }
      this.file = file;
    },
    removeFile() { this.file = null; this.errorMessage = ''; this.successMessage = ''; },
    resetForm() { this.removeFile() },
    formatFileSize(bytes) {
      if (bytes === 0) return '0 Bytes'; const k = 1024; const sizes = ['Bytes', 'KB', 'MB'];
      const i = Math.floor(Math.log(bytes) / Math.log(k)); return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    },
    async uploadFile() {
      if (!this.file) return; this.uploading = true; this.errorMessage = '';
      try {
        const formData = new FormData(); formData.append('file', this.file);
        const response = await axios.post('/import-data', formData, {
          headers: { 'Content-Type': 'multipart/form-data', 'Authorization': `Bearer ${this.authStore.token}` }
        });
        this.successMessage = response.data.message + ` (${response.data.rows_imported} data berhasil diproses)`;
      } catch (error) { this.errorMessage = error.response?.data?.message || 'Upload gagal.'; } 
      finally { this.uploading = false; }
    },
    async logout() { if (confirm('Logout?')) { await this.authStore.logout(); this.$router.push({ name: 'login' }) } }
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inria+Sans:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap');

.dashboard-layout { display: flex; min-height: 100vh; background: #f8fafc; font-family: 'Inria Sans', sans-serif; flex-direction: column; }
.main-wrapper { display: flex; padding-top: 90px; flex: 1; min-height: 100vh; }

/* FIX Layout */
.page-content { flex: 1; margin-left: 280px; display: flex; flex-direction: column; min-height: calc(100vh - 90px); transition: margin-left 0.3s ease; }
.page-content.full-width { margin-left: 90px; }
.content-container { padding: 2rem 4rem; flex: 1; width: 100%; }

.page-header { display: flex; justify-content: space-between; margin-bottom: 2rem; }
.page-title { color: #1B2376; font-size: 2rem; font-weight: 700; margin: 0; }
.page-subtitle { color: #64748b; margin-top: 0.2rem; font-size: 1rem; }
.breadcrumb { font-size: 0.9rem; color: #94a3b8; font-weight: 600; }
.breadcrumb .active { color: #1B2376; }

.import-card { background: white; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); padding: 3rem; min-height: 400px; display: flex; flex-direction: column; align-items: center; justify-content: center; }
.upload-area { width: 100%; max-width: 600px; border: 2px dashed #cbd5e1; border-radius: 12px; padding: 3rem; text-align: center; cursor: pointer; transition: all 0.3s; }
.upload-area:hover { border-color: #1B2376; background: #f8fafc; }
.icon-wrapper { color: #1B2376; margin-bottom: 1rem; opacity: 0.8; }
.upload-area h3 { margin: 0; color: #1e293b; font-size: 1.2rem; }
.text-muted { color: #94a3b8; margin: 0.5rem 0; }
.file-hint { font-size: 0.85rem; color: #F6983E; font-weight: 600; margin-top: 1rem; }

.file-review-area { width: 100%; max-width: 500px; text-align: center; }
.file-info-card { display: flex; align-items: center; background: #f1f5f9; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; text-align: left; }
.file-icon { font-size: 2rem; margin-right: 1rem; }
.file-details h4 { margin: 0; font-size: 1rem; color: #1e293b; }
.file-details span { font-size: 0.85rem; color: #64748b; }
.btn-remove { margin-left: auto; background: none; border: none; color: #ef4444; font-size: 1.2rem; cursor: pointer; }

.action-buttons { display: flex; gap: 1rem; justify-content: center; margin-top: 1rem; }
.btn-primary { background: #1B2376; color: white; border: none; padding: 0.8rem 2rem; border-radius: 8px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; }
.btn-secondary { background: white; border: 1px solid #e2e8f0; color: #64748b; padding: 0.8rem 2rem; border-radius: 8px; font-weight: 700; cursor: pointer; }
.btn-primary:disabled { opacity: 0.7; cursor: not-allowed; }

.success-area { text-align: center; }
.success-icon { font-size: 4rem; margin-bottom: 1rem; }
.success-area h3 { color: #10b981; margin: 0 0 0.5rem 0; }
.success-area p { color: #64748b; margin-bottom: 2rem; }
.alert-box { padding: 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.9rem; }
.alert-box.error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
.spinner-small { width: 16px; height: 16px; border: 2px solid rgba(255,255,255,0.3); border-top: 2px solid white; border-radius: 50%; animation: spin 1s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
</style>