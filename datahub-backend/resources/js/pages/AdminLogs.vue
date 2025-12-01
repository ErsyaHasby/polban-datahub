<template>
  <div class="dashboard-layout">
    <Navbar :user="authStore.user" @logout="logout" @toggle-sidebar="toggleSidebar" />

    <div class="main-wrapper">
      <Sidebar :isAdmin="true" :isOpen="isSidebarOpen" />

      <main class="page-content" :class="{ 'full-width': !isSidebarOpen }">
        <div class="content-container">
          <div class="page-header">
            <h1 class="page-title">Activity Logs</h1>
            <p class="page-subtitle">Riwayat aktivitas pengguna dalam sistem</p>
          </div>

          <div class="table-card">
            <div v-if="loading" class="state-loading">Memuat logs...</div>
            
            <table v-else class="custom-table">
              <thead>
                <tr>
                  <th width="15%">Waktu</th>
                  <th width="20%">Pengguna</th>
                  <th width="10%">Role</th>
                  <th width="40%">Aktivitas</th>
                  <th width="15%">IP Address</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="log in logs" :key="log.id">
                  <td style="white-space: nowrap; color: #6b7280;">
                    {{ formatDate(log.created_at) }}
                  </td>
                  <td>
                    <span class="font-bold text-dark">{{ log.user?.name || 'System' }}</span>
                  </td>
                  <td>
                    <span class="badge-role">{{ log.user?.role || '-' }}</span>
                  </td>
                  <td>
                    <div>
                      <span class="action-tag">{{ log.action }}</span>
                      <p class="desc-text">{{ log.description }}</p>
                    </div>
                  </td>
                  <td class="code-text">{{ log.ip_address }}</td>
                </tr>
                <tr v-if="logs.length === 0">
                  <td colspan="5" class="state-empty">Belum ada aktivitas tercatat.</td>
                </tr>
              </tbody>
            </table>
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
  components: { Navbar, Sidebar, Footer },
  setup() { return { authStore: useAuthStore() } },
  data() {
    return {
      // === REVISI: BACA MEMORI BROWSER ===
      isSidebarOpen: localStorage.getItem('sidebarState') === 'closed' ? false : true,
      loading: false,
      logs: []
    }
  },
  mounted() { this.fetchLogs() },
  methods: {
    // === REVISI: SIMPAN STATUS ===
    toggleSidebar() { 
      this.isSidebarOpen = !this.isSidebarOpen;
      localStorage.setItem('sidebarState', this.isSidebarOpen ? 'open' : 'closed');
    },
    async fetchLogs() {
      this.loading = true;
      try {
        const res = await axios.get('/admin/activity-logs', {
          headers: { Authorization: `Bearer ${this.authStore.token}` }
        });
        this.logs = res.data.data;
      } catch(e) { console.error(e) }
      finally { this.loading = false }
    },
    formatDate(d) {
      return new Date(d).toLocaleDateString('id-ID', { 
        day:'numeric', month:'short', hour:'2-digit', minute:'2-digit' 
      })
    },
    async logout() { await this.authStore.logout(); this.$router.push({name:'login'}) }
  }
}
</script>

<style scoped>
/* Reuse Style dari AdminReview agar konsisten */
.dashboard-layout { display: flex; flex-direction: column; min-height: 100vh; background: var(--bg); font-family: 'Inria Sans', sans-serif; }
.main-wrapper { display: flex; flex: 1; padding-top: 80px; }

/* === PERBAIKAN LAYOUT MINI SIDEBAR === */
.page-content { 
  flex: 1; 
  margin-left: 280px; 
  transition: margin-left 0.3s ease;
  display: flex;
  flex-direction: column;
  min-height: calc(100vh - 80px);
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

.page-title { font-size: 1.8rem; font-weight: 700; color: var(--text); margin-bottom: 0.2rem; }
.page-subtitle { color: var(--muted); font-size: 1rem; margin-bottom: 2rem; }

.table-card { background: var(--surface); border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); overflow: hidden; }
.custom-table { width: 100%; border-collapse: collapse; text-align: left; }
.custom-table th { background: #f9fafb; color: #374151; font-weight: 600; padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; text-transform: uppercase; font-size: 0.8rem; }
.custom-table td { 
  padding: 1rem 1.5rem; 
  border-bottom: 1px solid #f3f4f6; 
  color: #4b5563; 
  vertical-align: middle; 
  background: #fff; 
}

/* Mode gelap: baris tabel gelap, teks oranye tetap terbaca */
.dark-theme .custom-table td {
  background: #181818;
  color: var(--text);
}
.dark-theme .custom-table th {
  background: #232323;
  color: var(--muted);
}
.dark-theme .code-text {
  color: var(--accent);
  background: #232323;
}

.state-loading, .state-empty { padding: 3rem; text-align: center; color: #9ca3af; font-style: italic; }

.font-bold { font-weight: 600; }
.text-dark { color: var(--text); }
.badge-role { background: #e0e7ff; color: #3730a3; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; text-transform: uppercase; font-weight: bold; }
.action-tag { font-weight: bold; color: #1B2376; display: block; margin-bottom: 2px; font-size: 0.85rem; }
.desc-text { margin: 0; font-size: 0.9rem; line-height: 1.4; }
.code-text { font-family: monospace; background: #f3f4f6; padding: 2px 6px; border-radius: 4px; font-size: 0.85rem; }
</style>