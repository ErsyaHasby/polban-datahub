<template>
  <div class="dashboard-layout">
    <Navbar :user="authStore.user" @logout="logout" @toggle-sidebar="toggleSidebar" />

    <div class="main-wrapper">
      <Sidebar :isAdmin="true" :isOpen="isSidebarOpen" />

      <main class="page-content" :class="{ 'full-width': !isSidebarOpen }">
        <div class="content-container">
          
          <div class="page-header">
            <h1 class="page-title">Riwayat Import Data</h1>
            <p class="page-subtitle">Kelola semua riwayat upload dari participant</p>
          </div>

          <div class="table-card">
            <div v-if="loading" class="state-loading">Sedang memuat data...</div>
            
            <table v-else class="custom-table">
              <thead>
                <tr>
                  <th>Pengirim</th>
                  <th>Nama File</th>
                  <th>Total Baris</th>
                  <th>Tanggal Aksi</th>
                  <th>Status</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="file in files" :key="file.batch_id">
                  <td>
                    <div class="user-cell">
                      <div class="avatar-circle">{{ file.user_name.charAt(0) }}</div>
                      <span class="font-bold">{{ file.user_name }}</span>
                    </div>
                  </td>
                  <td class="text-dark">{{ file.filename }}</td>
                  <td>{{ file.total_rows }} data</td>
                  <td>{{ formatDate(file.created_at) }}</td>
                  <td>
                    <span :class="['badge', statusClass(file.status)]">
                      {{ file.status }}
                    </span>
                  </td>
                  <td class="action-cell">
                    <template v-if="file.status === 'pending'">
                      <button 
                        @click="openReview(file)" 
                        class="btn-action-review" 
                        title="Review Detail">
                        🔍 Review
                      </button>
                    </template>
                    <template v-else>
                      <span class="reviewed-label">Sudah di review</span>
                    </template>
                  </td>
                </tr>
                <tr v-if="files.length === 0">
                  <td colspan="6" class="state-empty">
                    Belum ada riwayat import data.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <Footer />
      </main>
    </div>

    <div v-if="detailModal" class="modal-backdrop">
      <div class="modal-card large">
        <div class="modal-header">
          <div>
            <h3>Review File: {{ detailModal.filename }}</h3>
            <p class="text-sm text-gray">Pengirim: {{ detailModal.user_name }}</p>
          </div>
          <button @click="closeModal" class="btn-close">✕</button>
        </div>
        <div class="modal-body scrollable">
          <table class="preview-table themed-table">
            <thead>
              <tr>
                <th>Kelas</th><th>Angkatan</th><th>Tgl Lahir</th><th>JK</th><th>Agama</th>
                <th>Kode Pos</th><th>SLTA</th><th>Jalur</th><th>Wilayah</th><th>Provinsi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="row in rows" :key="row.import_id">
                <td>{{ row.kelas }}</td><td>{{ row.angkatan }}</td><td>{{ row.tgl_lahir }}</td>
                <td>{{ row.jenis_kelamin }}</td><td>{{ row.agama }}</td><td>{{ row.kode_pos }}</td>
                <td>{{ row.nama_slta_raw }}</td><td>{{ row.nama_jalur_daftar_raw }}</td>
                <td>{{ row.nama_wilayah_raw }}</td><td>{{ row.provinsi_raw }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button @click="showRejectInput = true" class="btn-danger">Tolak File</button>
          <button @click="openApproveModal" class="btn-primary">Setujui File</button>
        </div>
      </div>
    </div>

    <!-- Modal Penolakan -->
    <CustomModal v-if="showRejectInput" @close="showRejectInput = false">
      <template #header>Alasan Penolakan</template>
      <textarea v-model="rejectNotes" class="form-textarea" placeholder="Contoh: Format salah pada kolom Tanggal..."></textarea>
      <template #footer>
        <button @click="showRejectInput = false" class="btn-secondary">Batal</button>
        <button @click="rejectBatch" class="btn-danger">Kirim Penolakan</button>
      </template>
    </CustomModal>

    <!-- Modal Konfirmasi Approve -->
    <CustomModal v-if="showApproveModal" @close="showApproveModal = false">
      <template #header>Konfirmasi</template>
      <div>Yakin setujui? Data akan masuk ke database utama.</div>
      <template #footer>
        <button @click="showApproveModal = false" class="btn-secondary">Batal</button>
        <button @click="approveBatch" class="btn-primary">Setujui</button>
      </template>
    </CustomModal>

    <!-- Modal Info -->
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
  components: { Navbar, Sidebar, Footer, CustomModal },
  setup() { return { authStore: useAuthStore() } },
  data() {
    return {
      // === REVISI: BACA MEMORI BROWSER AGAR TIDAK MEMBESAR SENDIRI ===
      isSidebarOpen: localStorage.getItem('sidebarState') === 'closed' ? false : true,
      
      loading: false, files: [], detailModal: null, rows: [], showRejectInput: false, rejectNotes: '',
      showApproveModal: false,
      modalInfo: null,
    }
  },
  mounted() { this.fetchPending() },
  methods: {
    // === REVISI: SIMPAN STATUS SAAT KLIK GARIS TIGA ===
    toggleSidebar() { 
      this.isSidebarOpen = !this.isSidebarOpen;
      localStorage.setItem('sidebarState', this.isSidebarOpen ? 'open' : 'closed');
    },
    
    async fetchPending() {
      this.loading = true;
      this.files = [];
      try {
        const res = await axios.get('/admin/pending-imports', {
          headers: { Authorization: `Bearer ${this.authStore.token}` }
        });
        this.files = res.data.data;
      } catch(e) { console.error(e) }
      finally { this.loading = false }
    },
    async openReview(file) {
      this.detailModal = file;
      try {
        const res = await axios.get(`/admin/pending-imports/${file.batch_id}`, {
           headers: { Authorization: `Bearer ${this.authStore.token}` }
        });
        this.rows = res.data.data;
      } catch(e) { alert("Gagal mengambil detail") }
    },
    closeModal() {
      this.detailModal = null; this.rows = []; this.showRejectInput = false;
    },
    openApproveModal() { this.showApproveModal = true },
    async approveBatch() {
      this.showApproveModal = false
      try {
        await axios.post(`/admin/approve/${this.detailModal.batch_id}`, {}, {
           headers: { Authorization: `Bearer ${this.authStore.token}` }
        });
        this.modalInfo = "Berhasil disetujui!";
        this.closeModal(); this.fetchPending();
      } catch(e) { this.modalInfo = "Gagal approve: " + (e.response?.data?.message || 'Error') }
    },
    async rejectBatch() {
      if(!this.rejectNotes) { this.modalInfo = "Isi alasan penolakan!"; return }
      try {
        await axios.post(`/admin/reject/${this.detailModal.batch_id}`, { notes: this.rejectNotes }, {
           headers: { Authorization: `Bearer ${this.authStore.token}` }
        });
        this.modalInfo = "File ditolak.";
        this.closeModal(); this.fetchPending();
      } catch(e) { this.modalInfo = "Gagal reject." }
    },
    async deleteBatch(batchId) {
      if (!confirm(`Yakin ingin menghapus seluruh batch data ${batchId}? Aksi ini TIDAK DAPAT DIBATALKAN!`)) return;
      try {
        await axios.delete(`/admin/delete-batch/${batchId}`, {
          headers: { Authorization: `Bearer ${this.authStore.token}` }
        });
        alert('Batch data berhasil dihapus.'); 
        this.fetchPending(); 
      } catch (e) {
        let errorMessage = 'Gagal menghapus batch.';
        if (e.response && e.response.data && e.response.data.message) {
             errorMessage = e.response.data.message;
        }
        if (e.response && e.response.status === 404 && errorMessage.includes('Batch tidak ditemukan')) {
             alert('Batch data berhasil dihapus. (Data sudah hilang di server)');
             this.fetchPending(); 
             return;
        }
        alert(errorMessage);
        console.error(e);
      }
    },
    statusClass(status) {
      if(status === 'approved') return 'badge-success';
      if(status === 'rejected') return 'badge-danger';
      return 'badge-warning';
    },
    formatDate(d) {
       return new Date(d).toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit' })
    },
    async logout() { await this.authStore.logout(); this.$router.push({name:'login'}) }
  }
}
</script>

<style scoped>
/* Style Konsisten */
.dashboard-layout { display: flex; flex-direction: column; min-height: 100vh; background: var(--bg); font-family: 'Inria Sans', sans-serif; }
.main-wrapper { display: flex; flex: 1; padding-top: 90px; }

/* === PERBAIKAN LAYOUT MINI SIDEBAR === */
.page-content { 
  flex: 1; 
  margin-left: 280px; 
  transition: margin-left 0.3s ease;
  display: flex;
  flex-direction: column;
  min-height: calc(100vh - 90px);
}

/* MARGIN 90px saat tertutup (PENTING) */
.page-content.full-width { margin-left: 90px; }

.content-container { 
  max-width: 1200px; 
  margin: 0 auto;
  flex: 1;
  width: 100%;
  padding: 2rem;
}
/* ======================== */

.page-title { font-size: 1.8rem; font-weight: 700; color: var(--text); }
.page-subtitle { color: var(--muted); font-size: 1rem; }
.table-card { background: var(--surface); border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); overflow: hidden; }
.custom-table { width: 100%; border-collapse: collapse; text-align: left; }
.custom-table th { background: #f9fafb; color: #374151; font-weight: 600; padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; text-transform: uppercase; font-size: 0.8rem; }
.custom-table td { padding: 1rem 1.5rem; border-bottom: 1px solid #f3f4f6; vertical-align: middle; background: #fff; }
/* Mode gelap: baris tabel gelap, teks oranye tetap terbaca */
.dark-theme .custom-table td {
  background: #181818;
  color: var(--text);
}
.dark-theme .custom-table th {
  background: #232323;
  color: #fff;
}
.user-cell { display: flex; align-items: center; gap: 10px; }
.avatar-circle { width: 32px; height: 32px; background: #1B2376; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; }
.font-bold { font-weight: 600; }
.text-dark { color: var(--text); font-weight: 500; }
.state-empty { padding: 3rem; text-align: center; color: #9ca3af; font-style: italic; }

.action-cell { text-align: center; display: flex; justify-content: center; gap: 8px; }
.btn-action-review { background: #1B2376; color: white; padding: 6px 10px; border-radius: 6px; border: none; cursor: pointer; font-size: 0.85rem; font-weight: 600; }
/* Hapus style btn-action-delete jika tidak dipakai */
/* Tambahkan style untuk label sudah di review */
.reviewed-label {
  display: inline-block;
  padding: 6px 14px;
  border-radius: 6px;
  background: #e5e7eb;
  color: #64748b;
  font-size: 0.95rem;
  font-weight: 600;
  cursor: default;
  border: none;
}
.dark-theme .reviewed-label {
  background: #232323;
  color: var(--muted);
}

.badge { padding: 4px 8px; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; text-transform: capitalize; }
.badge-success { background-color: #dcfce7; color: #166534; }
.badge-danger { background-color: #fee2e2; color: #991b1b; }
.badge-warning { background-color: #fff7ed; color: #9a3412; }

.modal-backdrop { 
  position: fixed; inset: 0; background: rgba(0,0,0,0.5); 
  display: flex; align-items: center; justify-content: center; z-index: 1000; 
}
.modal-card.large { 
  width: 95%; max-width: 1100px; height: 85vh; 
  background: var(--surface); border-radius: 16px; 
  padding: 2.5rem 2rem 2rem 2rem; 
  display: flex; flex-direction: column; 
  box-shadow: 0 8px 40px rgba(27,35,118,0.15);
  border: 1.5px solid #e5e7eb;
}
.modal-card.small { width: 400px; background: var(--surface); border-radius: 12px; padding: 1.5rem; }
.modal-body.scrollable { 
  flex: 1; overflow-y: auto; margin-top: 1rem; 
  border: 1px solid #e5e7eb; border-radius: 10px; 
  background: #fff;
  padding: 1.2rem 0.5rem;
}
.dark-theme .modal-card.large {
  background: #181818;
  border: 1.5px solid #232323;
}
.dark-theme .modal-body.scrollable {
  background: #181818;
  border-color: #232323;
}
.preview-table.themed-table {
  width: 100%; border-collapse: collapse; font-size: 0.95rem;
}
.preview-table.themed-table th, .preview-table.themed-table td {
  padding: 10px 12px;
  border-bottom: 1px solid #e5e7eb;
  background: #fff;
  color: #1B2376;
  transition: background 0.2s, color 0.2s;
}
.preview-table.themed-table th {
  background: #f9fafb;
  font-weight: 700;
  font-size: 0.95rem;
  text-align: left;
  position: sticky;
  top: 0;
  z-index: 2;
}
.preview-table.themed-table tr:hover td {
  background: #f3f4f6;
}
.dark-theme .preview-table.themed-table th, 
.dark-theme .preview-table.themed-table td {
  background: #181818 !important;
  color: #FF914D !important;
  border-bottom: 1px solid #232323;
}
.dark-theme .preview-table.themed-table th {
  background: #232323 !important;
  color: #FF914D !important;
}
.dark-theme .preview-table.themed-table tr:hover td {
  background: #232323 !important;
}
@media (max-width: 900px) {
  .modal-card.large { padding: 1rem 0.5rem; }
  .modal-body.scrollable { padding: 0.5rem 0.2rem; }
}
.btn-primary { background: var(--btn-bg); color: var(--btn-text); padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer; }
.btn-danger { background: #ef4444; color: white; padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer; }
.btn-secondary { background: #e5e7eb; color: #374151; padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer; }
.form-textarea { width: 100%; border: 1px solid #d1d5db; padding: 0.5rem; border-radius: 6px; min-height: 100px; margin-top: 10px; font-family: inherit; }
.modal-footer { margin-top: 1rem; display: flex; justify-content: flex-end; gap: 10px; }
.btn-close { background: none; border: none; font-size: 1.2rem; cursor: pointer; }
</style>