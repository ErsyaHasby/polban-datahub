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
              <h1 class="page-title">Activity Logs</h1>
              <p class="page-subtitle">Monitor jejak aktivitas pengguna di dalam sistem</p>
            </div>
            <div class="breadcrumb">
              <span>Admin</span> / <span class="active">Logs</span>
            </div>
          </div>

          <div class="toolbar-card">
            <div class="filter-label">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
              <span>Filter Aktivitas:</span>
            </div>
            
            <div class="select-wrapper">
              <select v-model="filterAction" @change="fetchLogs" class="custom-select">
                <option value="">Semua Aktivitas</option>
                <option value="login">Login</option>
                <option value="logout">Logout</option>
                <option value="import_data">Import Data</option>
                <option value="approve_data">Approve Data</option>
                <option value="reject_data">Reject Data</option>
                <option value="export_data">Export Data</option>
              </select>
              <svg class="select-arrow" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>
          </div>

          <div class="table-card">
            
            <div v-if="loading" class="state-loading">
              <div class="spinner"></div> Memuat logs...
            </div>

            <div v-else-if="logs.length === 0" class="state-empty">
              <div class="empty-icon">📝</div>
              <p>Belum ada data aktivitas tercatat.</p>
            </div>

            <table v-else class="custom-table">
              <thead>
                <tr>
                  <th width="5%">ID</th>
                  <th width="20%">User</th>
                  <th width="15%">Action</th>
                  <th width="30%">Deskripsi</th>
                  <th width="15%">IP Address</th>
                  <th width="15%">Waktu</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="log in logs" :key="log.id">
                  <td>#{{ log.id }}</td>
                  <td>
                    <div class="user-cell">
                      <div class="avatar-circle">{{ (log.user?.name || '?').charAt(0) }}</div>
                      <span>{{ log.user?.name || 'Unknown' }}</span>
                    </div>
                  </td>
                  <td>
                    <span :class="['badge', getActionClass(log.action)]">
                      {{ formatAction(log.action) }}
                    </span>
                  </td>
                  <td class="desc-cell">{{ log.description }}</td>
                  <td class="ip-cell">{{ log.ip_address }}</td>
                  <td class="time-cell">{{ formatDate(log.created_at) }}</td>
                </tr>
              </tbody>
            </table>

            <div v-if="pagination" class="pagination-footer">
              <span>
                Menampilkan halaman <strong>{{ pagination.current_page }}</strong> dari <strong>{{ pagination.last_page }}</strong>
              </span>
              <div class="pagination-controls">
                <button 
                  @click="changePage(pagination.current_page - 1)" 
                  :disabled="pagination.current_page === 1"
                >
                  Previous
                </button>
                <button 
                  @click="changePage(pagination.current_page + 1)" 
                  :disabled="pagination.current_page === pagination.last_page"
                >
                  Next
                </button>
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
  name: 'AdminLogs',
  components: {
    Navbar,
    Sidebar,
    Footer
  },
  data() {
    return {
      isSidebarOpen: true,
      loading: false,
      logs: [],
      pagination: null,
      filterAction: '',
      currentPage: 1,
    }
  },
  setup() {
    const authStore = useAuthStore()
    return { authStore }
  },
  mounted() {
    this.fetchLogs()
  },
  methods: {
    toggleSidebar() {
      this.isSidebarOpen = !this.isSidebarOpen
    },
    async fetchLogs() {
      this.loading = true
      try {
        const params = { page: this.currentPage }
        if (this.filterAction) {
          params.action = this.filterAction
        }

        const response = await axios.get('/admin/activity-logs', {
          params,
          headers: { 'Authorization': `Bearer ${this.authStore.token}` }
        })

        this.logs = response.data.data
        this.pagination = {
          current_page: response.data.current_page,
          last_page: response.data.last_page,
          per_page: response.data.per_page,
          total: response.data.total,
        }
      } catch (error) {
        console.error('Error fetching logs:', error)
      } finally {
        this.loading = false
      }
    },
    changePage(page) {
      if (page < 1 || page > this.pagination.last_page) return
      this.currentPage = page
      this.fetchLogs()
    },
    getActionClass(action) {
      const classes = {
        'login': 'badge-info',
        'logout': 'badge-secondary',
        'import_data': 'badge-primary',
        'approve_data': 'badge-success',
        'reject_data': 'badge-danger',
        'export_data': 'badge-warning',
      }
      return classes[action] || 'badge-default'
    },
    formatAction(action) {
      return action.replace('_', ' ').toUpperCase()
    },
    formatDate(dateString) {
      if (!dateString) return '-'
      return new Date(dateString).toLocaleString('id-ID', {
        day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit'
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

/* === LAYOUT UTAMA === */
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

/* === HEADER === */
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

/* === TOOLBAR === */
.toolbar-card {
  background: white;
  padding: 1rem 1.5rem;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.filter-label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #64748b;
  font-weight: 600;
}

.select-wrapper {
  position: relative;
  width: 250px;
}

.custom-select {
  width: 100%;
  padding: 0.6rem 2rem 0.6rem 1rem;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  appearance: none;
  background: #f8fafc;
  color: #1e293b;
  font-family: inherit;
  cursor: pointer;
  font-weight: 600;
}

.custom-select:focus {
  outline: none;
  border-color: #1B2376;
}

.select-arrow {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  color: #64748b;
  pointer-events: none;
}

/* === TABLE === */
.table-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  overflow: hidden;
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

/* User Avatar */
.user-cell { display: flex; align-items: center; gap: 0.8rem; font-weight: 600; }
.avatar-circle {
  width: 32px; height: 32px;
  background: #e0e7ff; color: #1B2376;
  border-radius: 50%; display: flex; align-items: center; justify-content: center;
  font-weight: bold; font-size: 0.9rem;
}

/* Badges */
.badge {
  padding: 0.3rem 0.8rem;
  border-radius: 50px;
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  display: inline-block;
  letter-spacing: 0.5px;
}

.badge-info { background: #eff6ff; color: #3b82f6; border: 1px solid #dbeafe; } /* Login */
.badge-secondary { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; } /* Logout */
.badge-primary { background: #eef2ff; color: #6366f1; border: 1px solid #e0e7ff; } /* Import */
.badge-success { background: #f0fdf4; color: #22c55e; border: 1px solid #dcfce7; } /* Approve */
.badge-danger { background: #fef2f2; color: #ef4444; border: 1px solid #fee2e2; } /* Reject */
.badge-warning { background: #fffbeb; color: #f59e0b; border: 1px solid #fef3c7; } /* Export */

/* Cells */
.desc-cell { color: #64748b; font-size: 0.9rem; }
.ip-cell { font-family: monospace; color: #475569; background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-size: 0.85rem; display: inline-block; }
.time-cell { color: #64748b; font-size: 0.85rem; white-space: nowrap; }

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
  padding: 0.5rem 1rem;
  margin-left: 0.5rem;
  border-radius: 8px;
  cursor: pointer;
  color: #1e293b;
  font-weight: 600;
  transition: all 0.2s;
}

.pagination-controls button:hover:not(:disabled) {
  border-color: #1B2376;
  color: #1B2376;
}

.pagination-controls button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  background: #f8fafc;
}

/* Loading & Empty */
.state-loading, .state-empty {
  text-align: center; padding: 3rem; color: #64748b;
}
.empty-icon { font-size: 3rem; margin-bottom: 1rem; opacity: 0.5; }
</style>