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
              <p>Unduh data mahasiswa yang sudah disetujui dalam format CSV</p>
            </div>

            <div class="card-right">
              <form @submit.prevent class="filter-form">
                <div class="form-group">
                  <label>Filter Pencarian</label>
                  <div class="single-search-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="input-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input 
                      type="text" v-model="searchQuery" 
                      @input="onSearchChange"
                      placeholder="Cari nama file di riwayat import" class="form-input-single"
                    >
                  </div>
                </div>
                <!-- Hapus tombol submit global karena download per-file -->
                <!-- ...existing code... -->
              </form>

              <!-- HASIL PENCARIAN -->
              <div style="margin-top: 1.5rem;">
                <div v-if="filesLoading" class="state-loading">Memuat daftar file...</div>
                <div v-else>
                  <div v-if="files.length === 0" class="state-empty">Tidak ada file yang cocok.</div>
                  <table v-else class="custom-table themed-table">
                    <thead>
                      <tr>
                        <th>Nama File</th>
                        <th>Pengunggah</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="f in files" :key="f.batch_id">
                        <td class="font-bold text-dark">{{ f.filename }}</td>
                        <td>{{ f.user_name }}</td>
                        <td>{{ formatDate(f.created_at) }}</td>
                        <td><span :class="['badge', statusClass(f.status)]">{{ f.status }}</span></td>
                        <td class="text-right">
                          <button class="btn-download" style="padding: .5rem .9rem; font-size:.95rem"
                                  @click="downloadBatch(f)"
                                  :disabled="downloadingId === f.batch_id">
                            <span v-if="downloadingId === f.batch_id" class="spinner"></span>
                            <span v-else>Download</span>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <Footer />
      </main>
    </div>

    <CustomModal v-if="modalInfo" @close="modalInfo = null">
      <template #header>Info</template>
      <div>{{ modalInfo }}</div>
      <template #footer>
        <button @click="modalInfo = null" class="btn-primary">OK</button>
      </template>
    </CustomModal>
  </div>
</template>

<script>
import axios from 'axios'
import { useAuthStore } from '../stores/auth'
import Navbar from '../components/Navbar.vue'
import Sidebar from '../components/Sidebar.vue'
import Footer from '../components/Footer.vue'
import CustomModal from '../components/CustomModal.vue'

export default {
  name: 'DownloadData',
  components: { Navbar, Sidebar, Footer, CustomModal },
  data() {
    return {
      // 1. LOGIC PENTING: Baca localStorage
      isSidebarOpen: localStorage.getItem('sidebarState') === 'closed' ? false : true,
      loading: false,
      searchQuery: '',
      files: [],
      filesLoading: false,
      downloadingId: null,
      modalInfo: null,
      _searchDebounce: null
    }
  },
  setup() {
    const authStore = useAuthStore()
    return { authStore }
  },
  mounted() {
    this.fetchFiles()
  },
  methods: {
    // 2. LOGIC PENTING: Simpan ke localStorage
    toggleSidebar() { 
      this.isSidebarOpen = !this.isSidebarOpen;
      localStorage.setItem('sidebarState', this.isSidebarOpen ? 'open' : 'closed');
    },
    onSearchChange() {
      clearTimeout(this._searchDebounce)
      this._searchDebounce = setTimeout(() => this.fetchFiles(), 350)
    },
    async fetchFiles() {
      this.filesLoading = true
      try {
        // 1) Ambil dari endpoint approved-batches untuk data yang sudah disetujui
        const resAdmin = await axios.get('/admin/approved-batches', {
          params: { search: this.searchQuery || '' },
          headers: { Authorization: `Bearer ${this.authStore.token}` }
        })
        let list = Array.isArray(resAdmin.data?.data) ? resAdmin.data.data : []

        // 2) Normalisasi field agar tabel konsisten
        this.files = (list || []).map(it => ({
          batch_id: it.batch_id ?? it.id ?? it.batchId,
          filename: it.filename ?? it.file_name ?? 'unknown.csv',
          user_name: it.user_name ?? it.user?.name ?? 'Unknown',
          created_at: it.created_at ?? it.uploaded_at ?? new Date().toISOString(),
          status: it.status ?? 'approved',
          total_rows: it.total_rows ?? it.rows ?? null,
        }))
      } catch (e) {
        console.error(e)
        this.files = []
      } finally {
        this.filesLoading = false
      }
    },
    async downloadBatch(file) {
      this.downloadingId = file.batch_id
      try {
        const res = await axios.get(`/admin/files/${file.batch_id}/download`, {
          responseType: 'blob',
          headers: { 'Authorization': `Bearer ${this.authStore.token}`, 'Accept': '*/*' }
        })
        const blob = new Blob([res.data], { type: res.headers['content-type'] || 'application/octet-stream' })
        let filename = file.filename?.replace(/\.(xlsx|xls|csv)$/i, '') + '.csv'
        const disposition = res.headers?.['content-disposition']
        if (disposition) {
          const match = /filename\*?=(?:UTF-8'')?"?([^\";]+)"?/i.exec(disposition)
          if (match && match[1]) filename = decodeURIComponent(match[1])
        }
        const url = URL.createObjectURL(blob)
        const a = document.createElement('a')
        a.href = url; a.download = filename
        document.body.appendChild(a); a.click()
        document.body.removeChild(a); URL.revokeObjectURL(url)
      } catch (e) {
        let msg = 'Gagal mengunduh file.'
        try {
          const data = e.response?.data
          if (data instanceof Blob) { const t = await data.text(); try { msg = JSON.parse(t)?.message || msg } catch { msg = t || msg } }
          else if (typeof data === 'object' && data?.message) { msg = data.message }
        } catch {}
        this.modalInfo = msg
      } finally {
        this.downloadingId = null
      }
    },
    statusClass(s) { if(s==='approved') return 'badge-success'; if(s==='rejected') return 'badge-danger'; return 'badge-warning' },
    formatDate(d) { return new Date(d).toLocaleDateString('id-ID',{ day:'numeric', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit'}) },
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
  background-color: var(--bg);
  display: flex; flex-direction: column;
}
.main-wrapper { display: flex; padding-top: 90px; flex: 1; min-height: 100vh; }

.page-content {
  flex: 1; margin-left: 280px; 
  display: flex; flex-direction: column;
  min-height: calc(100vh - 90px);
  transition: margin-left 0.3s ease-in-out;
}

/* MARGIN 90px SAAT MINI SIDEBAR */
.page-content.full-width { margin-left: 90px; }

.content-container { padding: 2rem 4rem; flex: 1; width: 100%; }

.page-header { display: flex; justify-content: space-between; margin-bottom: 2rem; }
.page-title { color: var(--text); font-size: 2rem; font-weight: 700; margin: 0; }
.page-subtitle { color: var(--muted); margin-top: 0.2rem; font-size: 1rem; }
.breadcrumb { font-size: 0.9rem; color: var(--muted); font-weight: 600; }
.breadcrumb .active { color: var(--text); }

.download-card {
  background: var(--surface); border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.05);
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
.form-group label { color: var(--text); font-weight: 700; font-size: 1rem; }

.single-search-wrapper { display: flex; align-items: center; position: relative; }
.input-icon { position: absolute; left: 1rem; color: var(--muted); pointer-events: none; }
.form-input-single {
  width: 100%; padding: 1rem 1rem 1rem 3rem;
  border: 2px solid #e2e8f0; border-radius: 10px; font-family: inherit;
  font-size: 1.1rem; transition: border-color 0.3s; outline: none;
  background: var(--surface); color: var(--text);
}
.form-input-single:focus { border-color: var(--accent); }

.btn-download {
  margin-top: 1rem;
  background-color: #1B2376; /* BIRU untuk mode terang */
  color: #fff;
  border: none;
  padding: 1rem;
  border-radius: 10px;
  font-weight: 700;
  font-size: 1.1rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.8rem;
  transition: transform 0.2s, box-shadow 0.2s;
}
.btn-download:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(27, 35, 118, 0.25); /* Biru shadow */
}
.btn-download:disabled {
  background-color: #cbd5e1;
  cursor: not-allowed;
}

/* Mode gelap: tombol oranye, background card gelap */
.dark-theme .btn-download {
  background-color: var(--accent);
  color: var(--accent-contrast);
  box-shadow: 0 5px 15px rgba(255, 122, 0, 0.18);
}
.dark-theme .download-card {
  background: #232323;
}
.dark-theme .card-right label {
  color: var(--text);
}
.spinner {
  border: 3px solid rgba(255,255,255,0.3); border-radius: 50%;
  border-top: 3px solid white; width: 20px; height: 20px; animation: spin 1s linear infinite;
}
@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

.custom-table { width: 100%; border-collapse: collapse; text-align: left; }
.custom-table.themed-table th, .custom-table.themed-table td {
  background: #fff;
  color: #1B2376;
  border-bottom: 1px solid #e5e7eb;
  padding: 1rem 1.2rem;
  transition: background 0.2s, color 0.2s;
}
.custom-table.themed-table th {
  background: #f9fafb;
  font-weight: 700;
  font-size: 0.95rem;
  text-align: left;
}
.custom-table.themed-table tr:hover td {
  background: #f3f4f6;
}
.dark-theme .custom-table.themed-table th, 
.dark-theme .custom-table.themed-table td {
  background: #181818 !important;
  color: #FF914D !important;
  border-bottom: 1px solid #232323;
}
.dark-theme .custom-table.themed-table th {
  background: #232323 !important;
  color: #FF914D !important;
}
.dark-theme .custom-table.themed-table tr:hover td {
  background: #232323 !important;
}
.custom-table th { background-color: #f9fafb; color: #374151; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; padding: .8rem 1rem; border-bottom: 1px solid #e5e7eb; }
.custom-table td { padding: .8rem 1rem; border-bottom: 1px solid #f3f4f6; color: #4b5563; font-size: .95rem; vertical-align: middle; background: #fff; }
.state-loading, .state-empty { padding: 1rem 0; color: #9ca3af; font-style: italic; }
.text-right { text-align: right; }
</style>