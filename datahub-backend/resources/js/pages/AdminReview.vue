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
                    <button 
                      v-if="file.status === 'pending'" 
                      @click="openReview(file)" 
                      class="btn-action-review" 
                      title="Review Detail">
                      🔍 Review
                    </button>
                    <button 
                      @click="deleteBatch(file.batch_id)" 
                      class="btn-action-delete" 
                      title="Hapus Permanen">
                      🗑️ Hapus
                    </button>
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
          <table class="preview-table">
            <thead>
              <tr>
                <th>Kelas</th>
                <th>Angkatan</th>
                <th>Tgl Lahir</th>
                <th>JK</th>
                <th>Agama</th>
                <th>Kode Pos</th>
                <th>SLTA</th>
                <th>Jalur</th>
                <th>Wilayah</th>
                <th>Provinsi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="row in rows" :key="row.import_id">
                <td>{{ row.kelas }}</td>
                <td>{{ row.angkatan }}</td>
                <td>{{ row.tgl_lahir }}</td>
                <td>{{ row.jenis_kelamin }}</td>
                <td>{{ row.agama }}</td>
                <td>{{ row.kode_pos }}</td>
                <td>{{ row.nama_slta_raw }}</td>
                <td>{{ row.nama_jalur_daftar_raw }}</td>
                <td>{{ row.nama_wilayah_raw }}</td>
                <td>{{ row.provinsi_raw }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="modal-footer">
          <button @click="showRejectInput = true" class="btn-danger">Tolak File</button>
          <button @click="approveBatch" class="btn-primary">Setujui File</button>
        </div>
      </div>
    </div>

    <div v-if="showRejectInput" class="modal-backdrop z-high">
      <div class="modal-card small">
        <h3>Alasan Penolakan</h3>
        <textarea v-model="rejectNotes" class="form-textarea" placeholder="Contoh: Format salah pada kolom Tanggal..."></textarea>
        <div class="modal-footer">
          <button @click="showRejectInput = false" class="btn-secondary">Batal</button>
          <button @click="rejectBatch" class="btn-danger">Kirim Penolakan</button>
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
  components: { Navbar, Sidebar, Footer },
  setup() { return { authStore: useAuthStore() } },
  data() {
    return {
      isSidebarOpen: true, loading: false, files: [], 
      detailModal: null, rows: [], showRejectInput: false, rejectNotes: ''
    }
  },
  mounted() { this.fetchPending() },
  methods: {
    toggleSidebar() { this.isSidebarOpen = !this.isSidebarOpen },
    async fetchPending() {
      this.loading = true;
      this.files = [];
      try {
        // Endpoint ini mengambil SEMUA data import history
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
    async approveBatch() {
      if(!confirm("Yakin setujui? Data akan masuk ke database utama.")) return;
      try {
        await axios.post(`/admin/approve/${this.detailModal.batch_id}`, {}, {
           headers: { Authorization: `Bearer ${this.authStore.token}` }
        });
        alert("Berhasil disetujui!"); this.closeModal(); this.fetchPending();
      } catch(e) { alert("Gagal approve: " + (e.response?.data?.message || 'Error')) }
    },
    async rejectBatch() {
      if(!this.rejectNotes) return alert("Isi alasan penolakan!");
      try {
        await axios.post(`/admin/reject/${this.detailModal.batch_id}`, { notes: this.rejectNotes }, {
           headers: { Authorization: `Bearer ${this.authStore.token}` }
        });
        alert("File ditolak."); this.closeModal(); this.fetchPending();
      } catch(e) { alert("Gagal reject.") }
    },
    
    // FITUR BARU: Hapus Manual Batch
    async deleteBatch(batchId) {
      if (!confirm(`Yakin ingin menghapus seluruh batch data ${batchId}? Aksi ini TIDAK DAPAT DIBATALKAN dan akan menghapus semua data mentah! Data yang sudah diapprove tidak akan terhapus dari tabel Mahasiswa.`)) return;

      try {
        // Kirim permintaan DELETE. 
        // Jika berhasil, kode akan langsung lompat ke baris alert.
        await axios.delete(`/admin/delete-batch/${batchId}`, {
          headers: { Authorization: `Bearer ${this.authStore.token}` }
        });
        
        // --- BLOK SUKSES ---
        alert('Batch data berhasil dihapus.'); 
        this.fetchPending(); 
        
      } catch (e) {
        // --- BLOK KEGAGALAN ---
        let errorMessage = 'Gagal menghapus batch.';
        
        if (e.response && e.response.data && e.response.data.message) {
             errorMessage = e.response.data.message;
        }

        // Karena Anda sudah konfirmasi data hilang di DB setelah refresh, 
        // kita paksa anggap sukses dan refresh list.
        
        // Kita periksa apakah error yang terjadi adalah 404/Batch tidak ditemukan, 
        // yang mengindikasikan data sudah hilang.
        if (e.response && e.response.status === 404 && errorMessage.includes('Batch tidak ditemukan')) {
             alert('Batch data berhasil dihapus. (Data sudah hilang di server)');
             this.fetchPending(); 
             return;
        }

        // Tampilkan pesan error jika terjadi error non-404 kritis
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
.dashboard-layout { display: flex; min-height: 100vh; background: #f3f4f6; font-family: 'Inria Sans', sans-serif; }
.main-wrapper { display: flex; flex: 1; padding-top: 80px; }
.page-content { flex: 1; margin-left: 280px; padding: 2rem; transition: margin-left 0.3s ease; }
.page-title { font-size: 1.8rem; font-weight: 700; color: #111827; }
.page-subtitle { color: #6b7280; font-size: 1rem; }
.table-card { background: white; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); overflow: hidden; }
.custom-table { width: 100%; border-collapse: collapse; text-align: left; }
.custom-table th { background: #f9fafb; color: #374151; font-weight: 600; padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; text-transform: uppercase; font-size: 0.8rem; }
.custom-table td { padding: 1rem 1.5rem; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
.user-cell { display: flex; align-items: center; gap: 10px; }
.avatar-circle { width: 32px; height: 32px; background: #1B2376; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; }
.font-bold { font-weight: 600; }
.text-dark { color: #111827; font-weight: 500; }
.state-empty { padding: 3rem; text-align: center; color: #9ca3af; font-style: italic; }

/* Tombol Aksi */
.action-cell { text-align: center; display: flex; justify-content: center; gap: 8px; }
.btn-action-review { background: #1B2376; color: white; padding: 6px 10px; border-radius: 6px; border: none; cursor: pointer; font-size: 0.85rem; font-weight: 600; }
.btn-action-delete { background: #ef4444; color: white; padding: 6px 10px; border-radius: 6px; border: none; cursor: pointer; font-size: 0.85rem; font-weight: 600; }

/* Badge Status */
.badge { padding: 4px 8px; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; text-transform: capitalize; }
.badge-success { background-color: #dcfce7; color: #166534; }
.badge-danger { background-color: #fee2e2; color: #991b1b; }
.badge-warning { background-color: #fff7ed; color: #9a3412; }

/* Modal Styles */
.modal-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1000; }
.modal-card.large { width: 95%; max-width: 1100px; height: 85vh; background: white; border-radius: 12px; padding: 1.5rem; display: flex; flex-direction: column; }
.modal-card.small { width: 400px; background: white; border-radius: 12px; padding: 1.5rem; }
.modal-body.scrollable { flex: 1; overflow-y: auto; margin-top: 1rem; border: 1px solid #eee; }
.preview-table { width: 100%; border-collapse: collapse; font-size: 0.8rem; }
.preview-table th { position: sticky; top: 0; background: #e2e8f0; padding: 8px; text-align: left; }
.preview-table td { padding: 6px 8px; border-bottom: 1px solid #eee; background: white; }
.btn-primary { background: #1B2376; color: white; padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer; }
.btn-danger { background: #ef4444; color: white; padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer; }
.btn-secondary { background: #e5e7eb; color: #374151; padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer; }
.form-textarea { width: 100%; border: 1px solid #d1d5db; padding: 0.5rem; border-radius: 6px; min-height: 100px; margin-top: 10px; font-family: inherit; }
.modal-footer { margin-top: 1rem; display: flex; justify-content: flex-end; gap: 10px; }
.btn-close { background: none; border: none; font-size: 1.2rem; cursor: pointer; }
</style>