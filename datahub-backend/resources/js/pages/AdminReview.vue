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
              <h1 class="page-title">Review Data</h1>
              <p class="page-subtitle">Kelola persetujuan data yang diimpor oleh participant</p>
            </div>
            <div class="breadcrumb">
              <span>Admin</span> / <span class="active">Review</span>
            </div>
          </div>

          <div class="toolbar-card">
            <div class="search-wrapper">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="search-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
              <input type="text" placeholder="Cari data (Nama, Kelas, Angkatan)..." class="search-input" v-model="searchQuery">
            </div>
            <button class="btn-filter">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
              Filter
            </button>
          </div>

          <div class="table-card">
            
            <div v-if="loading" class="state-loading">
              <div class="spinner"></div> Memuat data...
            </div>

            <div v-else-if="filteredImports.length === 0" class="state-empty">
              <div class="empty-icon">📭</div>
              <p>Tidak ada data pending saat ini.</p>
            </div>

            <table v-else class="custom-table">
              <thead>
                <tr>
                  <th width="5%">No</th>
                  <th width="20%">Importer</th>
                  <th width="25%">Detail Kelas</th>
                  <th width="15%">Angkatan</th>
                  <th width="15%">Tanggal Import</th>
                  <th width="10%">Status</th>
                  <th width="10%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in filteredImports" :key="item.id">
                  <td>{{ index + 1 }}</td>
                  <td>
                    <div class="user-cell">
                      <div class="avatar-circle">{{ item.user?.name.charAt(0) }}</div>
                      <span>{{ item.user?.name }}</span>
                    </div>
                  </td>
                  <td>
                    <span class="text-primary">{{ item.kelas || '-' }}</span>
                    <br>
                    <span class="text-small">{{ item.nama_slta_raw || 'SLTA Tidak Diketahui' }}</span>
                  </td>
                  <td>{{ item.angkatan || '-' }}</td>
                  <td>{{ formatDate(item.created_at) }}</td>
                  <td>
                    <span class="badge badge-pending">Pending</span>
                  </td>
                  <td>
                    <div class="action-buttons">
                      <button @click="showDetail(item)" class="btn-icon btn-detail" title="Detail">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                      </button>
                      <button @click="approveImport(item.id)" class="btn-icon btn-approve" title="Approve">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                      </button>
                      <button @click="showRejectModal(item)" class="btn-icon btn-reject" title="Reject">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            
            <div class="pagination-footer" v-if="filteredImports.length > 0">
              <span>Showing 1 to {{ filteredImports.length }} entries</span>
              <div class="pagination-controls">
                <button disabled>Previous</button>
                <button class="active">1</button>
                <button disabled>Next</button>
              </div>
            </div>

          </div>

        </div>

        <Footer />
      </main>

    </div>

    <div v-if="detailModal" class="modal-backdrop" @click="detailModal = null">
      <div class="modal-card" @click.stop>
        <div class="modal-header">
          <h3>Detail Import Data</h3>
          <button @click="detailModal = null" class="btn-close">✕</button>
        </div>
        <div class="modal-body">
          <div class="detail-grid">
            <div class="detail-row">
              <span class="label">Kelas</span>
              <span class="value">{{ detailModal.kelas || '-' }}</span>
            </div>
            <div class="detail-row">
              <span class="label">Angkatan</span>
              <span class="value">{{ detailModal.angkatan || '-' }}</span>
            </div>
            <div class="detail-row">
              <span class="label">Asal Sekolah</span>
              <span class="value">{{ detailModal.nama_slta_raw || '-' }}</span>
            </div>
            <div class="detail-row">
              <span class="label">Jalur Daftar</span>
              <span class="value">{{ detailModal.nama_jalur_daftar_raw || '-' }}</span>
            </div>
            <div class="detail-row">
              <span class="label">Wilayah</span>
              <span class="value">{{ detailModal.nama_wilayah_raw || '-' }}</span>
            </div>
            <div class="detail-row">
              <span class="label">Provinsi</span>
              <span class="value">{{ detailModal.provinsi_raw || '-' }}</span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button @click="detailModal = null" class="btn-secondary">Tutup</button>
          <button @click="approveImport(detailModal.id)" class="btn-primary">Approve Sekarang</button>
        </div>
      </div>
    </div>

    <div v-if="rejectModal" class="modal-backdrop" @click="rejectModal = null">
      <div class="modal-card" @click.stop>
        <div class="modal-header bg-red">
          <h3 class="text-white">Tolak Data</h3>
          <button @click="rejectModal = null" class="btn-close text-white">✕</button>
        </div>
        <div class="modal-body">
          <p class="mb-2">Silakan tulis alasan penolakan untuk data ini:</p>
          <textarea 
            v-model="rejectNotes" 
            rows="4" 
            class="form-textarea"
            placeholder="Contoh: Data tidak lengkap..."
          ></textarea>
        </div>
        <div class="modal-footer">
          <button @click="rejectModal = null" class="btn-secondary">Batal</button>
          <button @click="confirmReject" class="btn-danger">Tolak Data</button>
        </div>
      </div>
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
  name: 'AdminReview',
  components: {
    Navbar,
    Sidebar,
    Footer
  },
  data() {
    return {
      isSidebarOpen: true,
      loading: false,
      pendingImports: [],
      searchQuery: '', // Untuk fitur pencarian visual
      detailModal: null,
      rejectModal: null,
      rejectNotes: '',
    }
  },
  setup() {
    const authStore = useAuthStore()
    return { authStore }
  },
  mounted() {
    this.fetchPendingImports()
  },
  computed: {
    // Fitur Filter Sederhana di Client Side
    filteredImports() {
      if (!this.searchQuery) return this.pendingImports
      const query = this.searchQuery.toLowerCase()
      return this.pendingImports.filter(item => 
        (item.user?.name || '').toLowerCase().includes(query) ||
        (item.kelas || '').toLowerCase().includes(query) ||
        (item.angkatan?.toString() || '').includes(query)
      )
    }
  },
  methods: {
    toggleSidebar() {
      this.isSidebarOpen = !this.isSidebarOpen
    },
    async fetchPendingImports() {
      this.loading = true
      try {
        const response = await axios.get('/admin/pending-imports', {
          headers: { 'Authorization': `Bearer ${this.authStore.token}` }
        })
        this.pendingImports = response.data.data
      } catch (error) {
        console.error(error)
      } finally {
        this.loading = false
      }
    },
    showDetail(item) { this.detailModal = item },
    showRejectModal(item) { 
      this.rejectModal = item 
      this.rejectNotes = ''
    },
    async approveImport(id) {
      if (!confirm('Yakin ingin approve data ini?')) return
      try {
        await axios.post(`/admin/approve/${id}`, {}, {
          headers: { 'Authorization': `Bearer ${this.authStore.token}` }
        })
        this.detailModal = null
        this.fetchPendingImports()
        alert('Data berhasil diapprove!')
      } catch (error) {
        alert('Gagal approve.')
      }
    },
    async confirmReject() {
      if (!this.rejectNotes.trim()) return alert('Alasan wajib diisi')
      try {
        await axios.post(`/admin/reject/${this.rejectModal.id}`, { notes: this.rejectNotes }, {
          headers: { 'Authorization': `Bearer ${this.authStore.token}` }
        })
        this.rejectModal = null
        this.fetchPendingImports()
        alert('Data berhasil direject!')
      } catch (error) {
        alert('Gagal reject.')
      }
    },
    formatDate(dateString) {
      if (!dateString) return '-'
      return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'
      })
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

/* === LAYOUT UTAMA (Sama seperti Dashboard.vue) === */
.dashboard-layout {
  font-family: 'Inria Sans', sans-serif;
  min-height: 100vh;
  background-color: #f8fafc;
}

.main-wrapper {
  display: flex;
  padding-top: 90px;
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
  padding: 2rem 4rem;
  flex: 1;
}

/* === HEADER & BREADCRUMB === */
.page-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 2rem;
}

.page-title {
  color: #1B2376;
  font-size: 2rem;
  font-weight: 700;
  margin: 0;
}

.page-subtitle {
  color: #64748b;
  margin-top: 0.2rem;
  font-size: 1rem;
}

.breadcrumb {
  font-size: 0.9rem;
  color: #94a3b8;
  font-weight: 600;
}
.breadcrumb .active { color: #1B2376; }

/* === TOOLBAR (Search & Filter) === */
.toolbar-card {
  background: white;
  padding: 1rem 1.5rem;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.search-wrapper {
  display: flex;
  align-items: center;
  background: #f1f5f9;
  padding: 0.6rem 1rem;
  border-radius: 8px;
  width: 300px;
}

.search-icon { color: #94a3b8; margin-right: 0.5rem; }

.search-input {
  border: none;
  background: transparent;
  width: 100%;
  font-family: inherit;
  outline: none;
  font-size: 0.95rem;
  color: #1e293b;
}

.btn-filter {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: white;
  border: 1px solid #e2e8f0;
  padding: 0.6rem 1.2rem;
  border-radius: 8px;
  cursor: pointer;
  color: #64748b;
  font-weight: 600;
  transition: all 0.2s;
}
.btn-filter:hover { border-color: #1B2376; color: #1B2376; }

/* === TABLE STYLES === */
.table-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  overflow: hidden;
  padding: 0;
}

.custom-table {
  width: 100%;
  border-collapse: collapse;
}

.custom-table th {
  background: #f8fafc;
  color: #64748b;
  font-weight: 700;
  text-align: left;
  padding: 1.2rem;
  border-bottom: 2px solid #e2e8f0;
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.custom-table td {
  padding: 1.2rem;
  border-bottom: 1px solid #f1f5f9;
  color: #1e293b;
  vertical-align: middle;
}

.custom-table tr:hover { background: #fdfdfd; }

/* User Cell */
.user-cell { display: flex; align-items: center; gap: 0.8rem; font-weight: 600; }
.avatar-circle {
  width: 35px; height: 35px;
  background: #e0e7ff; color: #1B2376;
  border-radius: 50%; display: flex; align-items: center; justify-content: center;
  font-weight: bold; font-size: 1rem;
}

.text-primary { color: #1B2376; font-weight: 700; }
.text-small { font-size: 0.85rem; color: #94a3b8; }

/* Status Badge */
.badge {
  padding: 0.3rem 0.8rem;
  border-radius: 50px;
  font-size: 0.8rem;
  font-weight: 700;
  text-transform: uppercase;
}
.badge-pending { background: #fff7ed; color: #F6983E; border: 1px solid #ffedd5; }

/* Action Buttons */
.action-buttons { display: flex; gap: 0.5rem; }
.btn-icon {
  width: 32px; height: 32px;
  border-radius: 6px; border: none;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; transition: transform 0.2s;
}
.btn-icon:hover { transform: scale(1.1); }
.btn-detail { background: #e0f2fe; color: #0ea5e9; }
.btn-approve { background: #dcfce7; color: #10b981; }
.btn-reject { background: #fee2e2; color: #ef4444; }

/* Pagination */
.pagination-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  border-top: 1px solid #f1f5f9;
  color: #64748b;
  font-size: 0.9rem;
}

.pagination-controls button {
  background: white;
  border: 1px solid #e2e8f0;
  padding: 0.4rem 0.8rem;
  margin-left: 0.3rem;
  border-radius: 6px;
  cursor: pointer;
  color: #64748b;
}
.pagination-controls button.active {
  background: #1B2376; color: white; border-color: #1B2376;
}
.pagination-controls button:disabled { opacity: 0.5; cursor: not-allowed; }

/* === MODAL STYLE === */
.modal-backdrop {
  position: fixed; top: 0; left: 0; width: 100%; height: 100%;
  background: rgba(0,0,0,0.5);
  display: flex; align-items: center; justify-content: center; z-index: 1000;
}
.modal-card {
  background: white; width: 90%; max-width: 500px;
  border-radius: 15px; overflow: hidden;
  box-shadow: 0 10px 40px rgba(0,0,0,0.2);
  animation: slideUp 0.3s ease-out;
}
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

.modal-header {
  padding: 1.2rem 1.5rem; border-bottom: 1px solid #f1f5f9;
  display: flex; justify-content: space-between; align-items: center;
}
.modal-header h3 { margin: 0; font-size: 1.2rem; }
.modal-header.bg-red { background: #ef4444; color: white; border: none; }
.btn-close { background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #94a3b8; }
.text-white { color: white !important; }

.modal-body { padding: 1.5rem; }

.detail-row {
  display: flex; justify-content: space-between; padding: 0.8rem 0;
  border-bottom: 1px solid #f8fafc;
}
.detail-row:last-child { border-bottom: none; }
.label { color: #64748b; font-weight: 600; }
.value { color: #1e293b; font-weight: 700; text-align: right; }

.form-textarea {
  width: 100%; padding: 0.8rem; border: 1px solid #cbd5e1;
  border-radius: 8px; font-family: inherit; resize: none;
}

.modal-footer {
  padding: 1.2rem 1.5rem; background: #f8fafc;
  display: flex; justify-content: flex-end; gap: 0.8rem;
}

.btn-secondary { background: #e2e8f0; color: #475569; padding: 0.6rem 1.2rem; border-radius: 8px; border: none; cursor: pointer; font-weight: 700; }
.btn-primary { background: #1B2376; color: white; padding: 0.6rem 1.2rem; border-radius: 8px; border: none; cursor: pointer; font-weight: 700; }
.btn-danger { background: #ef4444; color: white; padding: 0.6rem 1.2rem; border-radius: 8px; border: none; cursor: pointer; font-weight: 700; }
</style>