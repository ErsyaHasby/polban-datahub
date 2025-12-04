<template>
  <div class="dashboard-layout">
    <Navbar :user="authStore.user" @logout="logout" @toggle-sidebar="toggleSidebar" />
    <div class="main-wrapper">
      <Sidebar :isParticipant="authStore.isParticipant" :isOpen="isSidebarOpen" />
      <main class="page-content" :class="{ 'full-width': !isSidebarOpen }">
        <div class="content-container">
          <div class="page-header">
            <div>
              <h1 class="page-title">Import Data</h1>
              <p class="page-subtitle">Unggah file CSV atau Excel untuk memasukkan data ke sistem.</p>
            </div>
            <div class="breadcrumb"><span>DataHub</span> / <span class="active">Import</span></div>
          </div>

          <div class="import-card">
            
            <div class="selection-area" v-if="!file && !successMessage">
              <label class="selection-label">1. Pilih Jenis Data</label>
              <select v-model="jenisData" class="data-select">
                <option value="" disabled>-- Silahkan Pilih --</option>
                <option value="mahasiswa">Data Mahasiswa Baru</option>
                <option value="akademik">Data Akademik (Nilai/KRS)</option>
              </select>

              <div v-if="jenisData" class="info-box-container">
                <div class="info-header">
                  <span class="info-icon">ℹ️</span>
                  <span class="info-title">Aturan & Contoh Data {{ jenisData === 'mahasiswa' ? 'Mahasiswa' : 'Akademik' }}</span>
                </div>
                
                <div class="table-responsive">
                  <table class="rules-table">
                    <thead>
                      <tr>
                        <th>Nama Kolom (Header)</th>
                        <th>Status</th>
                        <th>Contoh Data</th>
                        <th>Keterangan</th>
                      </tr>
                    </thead>
                    
                    <tbody v-if="jenisData === 'mahasiswa'">
                      <tr>
                        <td>Kelas</td>
                        <td><span class="badge required">Wajib</span></td>
                        <td><code>1A</code>, <code>2B</code></td>
                        <td>Kode kelas</td>
                      </tr>
                      <tr>
                        <td>Angkatan</td>
                        <td><span class="badge required">Wajib</span></td>
                        <td><code>2023</code></td>
                        <td>Tahun masuk (Angka)</td>
                      </tr>
                      <tr>
                        <td>Tanggal Lahir</td>
                        <td><span class="badge required">Wajib</span></td>
                        <td><code>2005-08-17</code></td>
                        <td>Format: YYYY-MM-DD</td>
                      </tr>
                      <tr>
                        <td>Jenis Kelamin</td>
                        <td><span class="badge required">Wajib</span></td>
                        <td><code>L</code> / <code>P</code></td>
                        <td>Laki-laki atau Perempuan</td>
                      </tr>
                      <tr>
                        <td>Asal SLTA</td>
                        <td><span class="badge required">Wajib</span></td>
                        <td><code>SMAN 1 BANDUNG</code></td>
                        <td>Harus sesuai master data</td>
                      </tr>
                      <tr>
                        <td>Jalur Daftar</td>
                        <td><span class="badge required">Wajib</span></td>
                        <td><code>SNBT</code></td>
                        <td>Jalur masuk</td>
                      </tr>
                      <tr>
                        <td>Wilayah</td>
                        <td><span class="badge required">Wajib</span></td>
                        <td><code>Kota Bandung</code></td>
                        <td>Kota/Kabupaten asal</td>
                      </tr>
                      <tr>
                        <td>Agama</td>
                        <td><span class="badge optional">Opsional</span></td>
                        <td><code>Islam</code></td>
                        <td>Boleh kosong</td>
                      </tr>
                      <tr>
                        <td>Kode Pos</td>
                        <td><span class="badge optional">Opsional</span></td>
                        <td><code>40559</code></td>
                        <td>Boleh kosong</td>
                      </tr>
                    </tbody>

                    <tbody v-else>
                      <tr>
                        <td>NIM</td>
                        <td><span class="badge required">Wajib</span></td>
                        <td><code>231511001</code></td>
                        <td>NIM Mahasiswa terdaftar</td>
                      </tr>
                      <tr>
                        <td>Kode Mata Kuliah</td>
                        <td><span class="badge required">Wajib</span></td>
                        <td><code>KO001</code></td>
                        <td>Kode unik MK</td>
                      </tr>
                      <tr>
                        <td>Nilai</td>
                        <td><span class="badge required">Wajib</span></td>
                        <td><code>A</code>, <code>85.5</code></td>
                        <td>Nilai Huruf/Angka</td>
                      </tr>
                      <tr>
                        <td>Semester</td>
                        <td><span class="badge required">Wajib</span></td>
                        <td><code>1</code></td>
                        <td>Semester pengambilan</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div v-if="!file && !successMessage" class="upload-area" :class="{'disabled': !jenisData}" @dragover.prevent @drop.prevent="handleDrop" @click="triggerFileInput">
              <input ref="fileInput" type="file" accept=".csv,.xlsx,.xls" @change="handleFileSelect" style="display: none" />
              <div class="icon-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
              </div>
              <h3>Drag & Drop file di sini</h3>
              <p class="text-muted">atau klik untuk memilih file dari komputer</p>
              <p class="file-hint" v-if="!jenisData" style="color: #ef4444;">(Pilih Jenis Data terlebih dahulu)</p>
              <p class="file-hint" v-else>Format: CSV, XLSX, XLS (Max: 10MB)</p>
            </div>

            <div v-else-if="file && !successMessage" class="file-review-area">
              <div class="file-info-card">
                <div class="file-icon">📄</div>
                <div class="file-details">
                  <h4>{{ file.name }}</h4>
                  <span>{{ formatFileSize(file.size) }} • Jenis: {{ jenisData }}</span>
                </div>
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
      isSidebarOpen: localStorage.getItem('sidebarState') === 'closed' ? false : true,
      jenisData: '', 
      file: null, 
      uploading: false, 
      errorMessage: '', 
      successMessage: ''
    }
  },
  methods: {
    toggleSidebar() { 
      this.isSidebarOpen = !this.isSidebarOpen;
      localStorage.setItem('sidebarState', this.isSidebarOpen ? 'open' : 'closed');
    },
    triggerFileInput() {
      if (this.jenisData) {
        this.$refs.fileInput.click();
      } else {
        alert("Mohon pilih jenis data (Mahasiswa/Akademik) terlebih dahulu.");
      }
    },
    handleFileSelect(event) { this.validateFile(event.target.files[0]) },
    handleDrop(event) { 
      if (!this.jenisData) {
        alert("Mohon pilih jenis data terlebih dahulu.");
        return;
      }
      this.validateFile(event.dataTransfer.files[0]) 
    },
    validateFile(file) {
      this.errorMessage = ''; if (!file) return;
      const allowedTypes = ['text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
      
      if (!allowedTypes.includes(file.type) && !file.name.match(/\.(csv|xlsx|xls)$/)) { 
        this.errorMessage = 'Format file tidak valid.'; return; 
      }
      if (file.size > 10 * 1024 * 1024) { 
        this.errorMessage = 'Ukuran file terlalu besar (Max 10MB).'; return; 
      }
      this.file = file;
    },
    removeFile() { this.file = null; this.errorMessage = ''; this.successMessage = ''; },
    resetForm() { this.removeFile(); this.jenisData = ''; },
    formatFileSize(bytes) {
      if (bytes === 0) return '0 Bytes'; const k = 1024; const sizes = ['Bytes', 'KB', 'MB'];
      const i = Math.floor(Math.log(bytes) / Math.log(k)); return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    },
    async uploadFile() {
      if (!this.file || !this.jenisData) return; 
      this.uploading = true; this.errorMessage = '';
      
      try {
        const formData = new FormData(); 
        formData.append('file', this.file);
        formData.append('jenis_data', this.jenisData);

        const response = await axios.post('/participant/upload', formData, {
          headers: { 'Content-Type': 'multipart/form-data', 'Authorization': `Bearer ${this.authStore.token}` }
        });
        
        this.successMessage = response.data.message;
        if(response.data.rows) {
             this.successMessage += ` (${response.data.rows} baris data diproses)`;
        }

      } catch (error) { 
        this.errorMessage = error.response?.data?.message || error.response?.data?.error || 'Upload gagal.'; 
      } 
      finally { this.uploading = false; }
    },
    async logout() { if (confirm('Logout?')) { await this.authStore.logout(); this.$router.push({ name: 'login' }) } }
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inria+Sans:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap');

.dashboard-layout { display: flex; min-height: 100vh; background: var(--bg); font-family: 'Inria Sans', sans-serif; flex-direction: column; }
.main-wrapper { display: flex; padding-top: 90px; flex: 1; min-height: 100vh; }
.page-content { flex: 1; margin-left: 280px; display: flex; flex-direction: column; min-height: calc(100vh - 90px); transition: margin-left 0.3s ease; }
.page-content.full-width { margin-left: 90px; }
.content-container { padding: 2rem 4rem; flex: 1; width: 100%; }

.page-header { display: flex; justify-content: space-between; margin-bottom: 2rem; }
.page-title { color: var(--text); font-size: 2rem; font-weight: 700; margin: 0; }
.page-subtitle { color: var(--muted); margin-top: 0.2rem; font-size: 1rem; }
.breadcrumb { font-size: 0.9rem; color: var(--muted); font-weight: 600; }
.breadcrumb .active { color: var(--text); }

.import-card { background: #fff; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); padding: 3rem; min-height: 400px; display: flex; flex-direction: column; align-items: center; justify-content: center; }

/* SELECTION AREA */
.selection-area { width: 100%; max-width: 800px; margin-bottom: 2rem; }
.selection-label { display: block; font-weight: bold; margin-bottom: 0.5rem; color: var(--text); font-size: 1.1rem; }
.data-select { width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; font-size: 1rem; cursor: pointer; max-width: 400px;}
.data-select:focus { outline: none; border-color: var(--accent); }

/* TABEL INFO */
.info-box-container { margin-top: 1.5rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden; }
.info-header { background: #eff6ff; padding: 1rem; display: flex; align-items: center; gap: 0.5rem; border-bottom: 1px solid #dbeafe; }
.info-title { font-weight: 700; color: #1e40af; font-size: 1rem; }
.table-responsive { overflow-x: auto; }
.rules-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
.rules-table th { text-align: left; padding: 0.8rem 1rem; background: #f1f5f9; color: #475569; font-weight: 700; border-bottom: 2px solid #e2e8f0; }
.rules-table td { padding: 0.8rem 1rem; border-bottom: 1px solid #e2e8f0; color: #334155; }
.rules-table tr:last-child td { border-bottom: none; }
code { background: #e2e8f0; padding: 2px 5px; border-radius: 4px; font-family: monospace; font-size: 0.85rem; color: #0f172a; }

.badge { display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
.badge.required { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
.badge.optional { background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; }

.upload-area { width: 100%; max-width: 600px; border: 2px dashed #cbd5e1; border-radius: 12px; padding: 3rem; text-align: center; cursor: pointer; transition: all 0.3s; background: var(--surface); color: var(--text); margin: 0 auto; }
.upload-area:hover:not(.disabled) { border-color: var(--accent); background: var(--bg); }
.upload-area.disabled { opacity: 0.5; cursor: not-allowed; background: #f1f5f9; }

.icon-wrapper { color: var(--accent); margin-bottom: 1rem; opacity: 0.8; }
.upload-area h3 { margin: 0; color: var(--text); font-size: 1.2rem; }
.text-muted { color: var(--muted); margin: 0.5rem 0; }
.file-hint { font-size: 0.85rem; color: var(--accent); font-weight: 600; margin-top: 1rem; }

.file-review-area { width: 100%; max-width: 500px; text-align: center; margin: 0 auto; }
.file-info-card { display: flex; align-items: center; background: #f1f5f9; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; text-align: left; }
.file-icon { font-size: 2rem; margin-right: 1rem; }
.file-details h4 { margin: 0; font-size: 1rem; color: var(--text); }
.file-details span { font-size: 0.85rem; color: var(--muted); }
.btn-remove { margin-left: auto; background: none; border: none; color: #ef4444; font-size: 1.2rem; cursor: pointer; }

.action-buttons { display: flex; gap: 1rem; justify-content: center; margin-top: 1rem; }
.btn-primary { background: var(--btn-bg); color: var(--btn-text); border: none; padding: 0.8rem 2rem; border-radius: 8px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; }
.btn-secondary { background: var(--surface); border: 1px solid #e2e8f0; color: var(--muted); padding: 0.8rem 2rem; border-radius: 8px; font-weight: 700; cursor: pointer; }
.btn-primary:disabled { opacity: 0.7; cursor: not-allowed; }

.success-area { text-align: center; }
.success-icon { font-size: 4rem; margin-bottom: 1rem; }
.success-area h3 { color: #10b981; margin: 0 0 0.5rem 0; }
.success-area p { color: var(--muted); margin-bottom: 2rem; }
.alert-box { padding: 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.9rem; }
.alert-box.error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
.spinner-small { width: 16px; height: 16px; border: 2px solid rgba(255,255,255,0.3); border-top: 2px solid white; border-radius: 50%; animation: spin 1s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }

/* Dark Mode */
.dark-theme .import-card { background: #232323; }
.dark-theme .info-box-container { background: #2a2a2a; border-color: #444; }
.dark-theme .info-header { background: #1e3a8a; border-color: #1e40af; }
.dark-theme .info-title { color: #bfdbfe; }
.dark-theme .rules-table th { background: #1f2937; color: #94a3b8; border-color: #374151; }
.dark-theme .rules-table td { color: #cbd5e1; border-color: #333; }
.dark-theme code { background: #334155; color: #e2e8f0; }
.dark-theme .badge.required { background: #7f1d1d; color: #fecaca; border-color: #991b1b; }
.dark-theme .badge.optional { background: #334155; color: #94a3b8; border-color: #475569; }
.dark-theme .data-select { background: #333; color: #fff; border-color: #444; }
.dark-theme .file-info-card { background: #181818; }
.dark-theme .upload-area { background: #232323; color: var(--text); }
.dark-theme .upload-area:hover:not(.disabled) { background: #181818; }
</style>