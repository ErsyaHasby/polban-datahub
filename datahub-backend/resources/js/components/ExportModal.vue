<template>
  <div class="modal-overlay" @click="$emit('close')">
    <div class="modal-content" @click.stop>
      <div class="modal-header">
        <h2>Export Data Mahasiswa</h2>
        <button @click="$emit('close')" class="btn-close">✕</button>
      </div>

      <div class="modal-body">
        <p class="modal-description">
          Filter data yang ingin di-export (opsional)
        </p>

        <div class="form-group">
          <label>Angkatan</label>
          <input v-model="filters.angkatan" type="number" placeholder="Contoh: 2024" />
        </div>

        <div class="form-group">
          <label>Kelas</label>
          <input v-model="filters.kelas" type="text" placeholder="Contoh: 1A" />
        </div>

        <div class="form-group">
          <label>Tahun</label>
          <input v-model="filters.tahun" type="number" placeholder="Contoh: 2025" />
        </div>

        <div v-if="errorMessage" class="error-message">
          {{ errorMessage }}
        </div>

        <div v-if="successMessage" class="success-message">
          {{ successMessage }}
        </div>
      </div>

      <div class="modal-footer">
        <button @click="$emit('close')" class="btn-cancel">Batal</button>
        <button 
          @click="exportData" 
          class="btn-export" 
          :disabled="exporting"
        >
          {{ exporting ? 'Exporting...' : 'Export Data' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { useAuthStore } from '../stores/auth'

export default {
  name: 'ExportModal',
  emits: ['close'],
  data() {
    return {
      filters: {
        angkatan: '',
        kelas: '',
        tahun: '',
      },
      exporting: false,
      errorMessage: '',
      successMessage: '',
    }
  },
  setup() {
    const authStore = useAuthStore()
    return { authStore }
  },
  methods: {
    async exportData() {
      this.exporting = true
      this.errorMessage = ''
      this.successMessage = ''

      try {
        // Build query params
        const params = {}
        if (this.filters.angkatan) params.angkatan = this.filters.angkatan
        if (this.filters.kelas) params.kelas = this.filters.kelas
        if (this.filters.tahun) params.tahun = this.filters.tahun

        const response = await axios.get('/export-data', {
          params,
          headers: {
            'Authorization': `Bearer ${this.authStore.token}`
          }
        })

        this.successMessage = `Export berhasil! Total ${response.data.total_records} records.`
        
        // For now, we just show the data
        // TODO: Implement actual file download
        console.log('Export data:', response.data.data)

        setTimeout(() => {
          this.$emit('close')
        }, 2000)

      } catch (error) {
        this.errorMessage = error.response?.data?.message || 'Export gagal. Silakan coba lagi.'
      } finally {
        this.exporting = false
      }
    }
  }
}
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  border-radius: 1rem;
  width: 90%;
  max-width: 500px;
  box-shadow: 0 10px 40px rgba(0,0,0,0.2);
}

.modal-header {
  padding: 1.5rem;
  border-bottom: 1px solid #e2e8f0;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-header h2 {
  margin: 0;
  color: #1e293b;
  font-size: 1.3rem;
}

.btn-close {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #64748b;
}

.modal-body {
  padding: 2rem;
}

.modal-description {
  color: #64748b;
  margin-bottom: 1.5rem;
}

.form-group {
  margin-bottom: 1.25rem;
}

.form-group label {
  display: block;
  font-weight: 600;
  color: #1e293b;
  margin-bottom: 0.5rem;
}

.form-group input {
  width: 93%;
  padding: 0.75rem;
  border: 1.5px solid #cbd5e1;
  border-radius: 0.5rem;
  font-size: 1rem;
}

.form-group input:focus {
  outline: none;
  border-color: #1B2376;
}

.error-message {
  background: #fee2e2;
  border: 1px solid #ef4444;
  color: #dc2626;
  padding: 0.75rem;
  border-radius: 0.5rem;
  margin-top: 1rem;
}

.success-message {
  background: #d1fae5;
  border: 1px solid #10b981;
  color: #059669;
  padding: 0.75rem;
  border-radius: 0.5rem;
  margin-top: 1rem;
}

.modal-footer {
  padding: 1.5rem;
  border-top: 1px solid #e2e8f0;
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
}

.btn-cancel {
  background: #f1f5f9;
  color: #64748b;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  cursor: pointer;
  font-weight: 600;
}

.btn-export {
  background: #10b981;
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  cursor: pointer;
  font-weight: 600;
}

.btn-export:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.btn-export:hover:not(:disabled) {
  background: #059669;
}
</style>
