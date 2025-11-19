<template>
  <div class="admin-page">
    <nav class="navbar">
      <div class="nav-brand"><h2>Polban <span>DataVerse</span></h2></div>
      <div class="nav-user">
        <router-link to="/" class="nav-link">← Dashboard</router-link>
        <span class="user-name">{{ authStore.user?.name }}</span>
        <button @click="logout" class="btn-logout">Logout</button>
      </div>
    </nav>

    <div class="page-content">
      <div class="page-header">
        <h1>Review Data Import</h1>
        <p>Approve atau reject FILE import dari participant</p>
      </div>

      <div v-if="loading" class="loading">Loading...</div>
      <div v-else-if="pendingImports.length === 0" class="empty-state">
        <div class="empty-icon">📭</div>
        <p>Tidak ada file pending untuk direview</p>
      </div>

      <div v-else class="imports-table">
        <table>
          <thead>
            <tr>
              <th>ID File</th>
              <th>Nama File</th>
              <th>Importer</th>
              <th>Tanggal Upload</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in pendingImports" :key="item.id">
              <td>#{{ item.id }}</td>
              <td>
                <div class="file-info">
                  <strong>{{ item.original_name }}</strong>
                  <span class="file-badge">File Document</span>
                </div>
              </td>
              <td>{{ item.user?.name }}</td>
              <td>{{ formatDate(item.created_at) }}</td>
              <td>
                <div class="action-buttons">
                  <button @click="fetchDetailAndShow(item.id)" class="btn-detail">Lihat Isi</button>
                  <button @click="approveImport(item.id)" class="btn-approve">Approve File</button>
                  <button @click="showRejectModal(item)" class="btn-reject">Reject</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div v-if="detailModal" class="modal-overlay" @click="closeDetail">
      <div class="modal-content large-modal" @click.stop>
        <div class="modal-header">
          <div>
            <h2>Preview Isi File</h2>
            <p class="modal-subtitle">{{ detailModal.original_name }} - Diupload oleh {{ detailModal.user?.name }}</p>
          </div>
          <button @click="closeDetail" class="btn-close">✕</button>
        </div>
        
        <div class="modal-body table-container">
          <table class="preview-table">
            <thead>
              <tr>
                <th>No</th>
                <th>Kelas</th>
                <th>Angkatan</th>
                <th>Nama SLTA</th>
                <th>Jalur</th>
                <th>Provinsi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(row, index) in detailModal.import_rows" :key="row.id">
                <td>{{ index + 1 }}</td>
                <td>{{ row.kelas }}</td>
                <td>{{ row.angkatan }}</td>
                <td>{{ row.nama_slta_raw }}</td>
                <td>{{ row.nama_jalur_daftar_raw }}</td>
                <td>{{ row.provinsi_raw }}</td>
              </tr>
              <tr v-if="!detailModal.import_rows || detailModal.import_rows.length === 0">
                <td colspan="6" class="text-center">Tidak ada data baris terbaca (File mungkin kosong/rusak)</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="modal-footer">
          <button @click="closeDetail" class="btn-cancel">Tutup</button>
          <button @click="approveImport(detailModal.id)" class="btn-approve-modal">
            Approve Seluruh File
          </button>
        </div>
      </div>
    </div>

    <div v-if="rejectModal" class="modal-overlay" @click="rejectModal = null">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h2>Reject File</h2>
          <button @click="rejectModal = null" class="btn-close">✕</button>
        </div>
        <div class="modal-body">
          <p>Berikan alasan penolakan untuk file ini:</p>
          <textarea v-model="rejectNotes" rows="4" placeholder="Contoh: Format salah, data duplikat..." required></textarea>
        </div>
        <div class="modal-footer">
          <button @click="rejectModal = null" class="btn-cancel">Batal</button>
          <button @click="confirmReject" class="btn-reject-modal">Reject File</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { useAuthStore } from '../stores/auth'

export default {
  name: 'AdminReview',
  data() {
    return {
      loading: false,
      pendingImports: [], // Ini sekarang list FILES
      detailModal: null,  // Ini object FILE + ROWS
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
  methods: {
    async fetchPendingImports() {
      this.loading = true
      try {
        const response = await axios.get('/admin/pending-imports', {
          headers: { 'Authorization': `Bearer ${this.authStore.token}` }
        })
        // Backend harus return list files
        this.pendingImports = response.data.data
      } catch (error) {
        console.error(error)
        alert('Gagal mengambil data.')
      } finally {
        this.loading = false
      }
    },
    async fetchDetailAndShow(id) {
      // Kita fetch detail lengkap (termasuk rows) saat tombol diklik
      try {
        const response = await axios.get(`/admin/pending-imports/${id}`, {
          headers: { 'Authorization': `Bearer ${this.authStore.token}` }
        })
        this.detailModal = response.data.data
      } catch (error) {
        alert('Gagal mengambil detail file.')
      }
    },
    closeDetail() {
      this.detailModal = null
    },
    showRejectModal(item) {
      this.rejectModal = item
      this.rejectNotes = ''
    },
    async approveImport(id) {
      if (!confirm('Yakin ingin approve FILE ini beserta seluruh datanya?')) return

      try {
        const response = await axios.post(`/admin/approve/${id}`, {}, {
          headers: { 'Authorization': `Bearer ${this.authStore.token}` }
        })
        alert(`Sukses! ${response.data.rows_processed} baris data berhasil masuk database.`)
        this.detailModal = null
        this.fetchPendingImports()
      } catch (error) {
        alert('Approve gagal: ' + (error.response?.data?.message || error.message))
      }
    },
    async confirmReject() {
      if (!this.rejectNotes.trim()) {
        alert('Isi alasan penolakan!')
        return
      }
      try {
        await axios.post(`/admin/reject/${this.rejectModal.id}`, {
          notes: this.rejectNotes
        }, {
          headers: { 'Authorization': `Bearer ${this.authStore.token}` }
        })
        alert('File berhasil di-reject.')
        this.rejectModal = null
        this.fetchPendingImports()
      } catch (error) {
        alert('Reject gagal.')
      }
    },
    formatDate(dateString) {
      if (!dateString) return '-'
      return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'
      })
    },
    logout() {
      if(confirm('Logout?')) { this.authStore.logout(); this.$router.push({name: 'login'}) }
    }
  }
}
</script>

<style scoped>
/* Style dasar sama, tambahkan style khusus tabel preview */
.large-modal {
  max-width: 900px; /* Modal lebih lebar untuk tabel */
  width: 95%;
}
.table-container {
  max-height: 60vh;
  overflow-y: auto;
  padding: 0;
}
.preview-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.85rem;
}
.preview-table th {
  background: #f1f5f9;
  position: sticky;
  top: 0;
  padding: 0.75rem;
  text-align: left;
  border-bottom: 2px solid #e2e8f0;
}
.preview-table td {
  padding: 0.5rem 0.75rem;
  border-bottom: 1px solid #e2e8f0;
}
.modal-subtitle {
  font-size: 0.85rem;
  color: #64748b;
  margin: 0;
}
/* ... Copy sisa style dari file lama Anda ... */
.admin-page { min-height: 100vh; background: #f1f5f9; }
.navbar { background: linear-gradient(to right, #1B2376, #2d3da6); color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
.imports-table { background: white; border-radius: 1rem; overflow: hidden; margin-top: 1rem; }
table { width: 100%; border-collapse: collapse; }
th, td { padding: 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; }
.action-buttons { display: flex; gap: 0.5rem; }
.btn-detail { background: #3b82f6; color: white; border: none; padding: 0.4rem 0.8rem; border-radius: 0.4rem; cursor: pointer; }
.btn-approve { background: #10b981; color: white; border: none; padding: 0.4rem 0.8rem; border-radius: 0.4rem; cursor: pointer; }
.btn-reject { background: #ef4444; color: white; border: none; padding: 0.4rem 0.8rem; border-radius: 0.4rem; cursor: pointer; }
.modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center; z-index: 999; }
.modal-content { background: white; border-radius: 0.5rem; overflow: hidden; display: flex; flex-direction: column; max-height: 90vh; }
.modal-header, .modal-footer { padding: 1rem; display: flex; justify-content: space-between; align-items: center; background: white; }
.modal-body { padding: 1rem; overflow-y: auto; }
textarea { width: 100%; border: 1px solid #ccc; padding: 0.5rem; border-radius: 0.25rem; }
</style>