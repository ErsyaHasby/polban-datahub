<template>
  <aside class="sidebar" :class="{ 'closed': !isOpen }">
    <div class="menu-list">
      
      <router-link v-if="isAdmin" to="/admin/review" class="menu-item" active-class="active">
        <div class="icon-box">🔍</div>
        <span class="menu-text">Review Data</span>
      </router-link>

      <router-link v-if="isAdmin" to="/admin/logs" class="menu-item" active-class="active">
        <div class="icon-box">📜</div>
        <span class="menu-text">Activity Logs</span>
      </router-link>

      <a v-if="isParticipant" href="#" @click.prevent="$emit('openImport')" class="menu-item">
        <div class="icon-box">📤</div>
        <span class="menu-text">Import Data</span>
      </a>

      <router-link v-if="isParticipant" to="/my-uploads" class="menu-item" active-class="active">
        <div class="icon-box">
           <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
        </div>
        <span class="menu-text">Riwayat Upload</span>
      </router-link>

      <router-link to="/download-data" class="menu-item" active-class="active">
        <div class="icon-box">💾</div>
        <span class="menu-text">Download Data</span>
      </router-link>

    </div>
    </aside>
</template>

<script>
export default {
  name: 'Sidebar',
  props: {
    isAdmin: Boolean,
    isParticipant: Boolean,
    isOpen: Boolean // Menerima status buka/tutup
  }
}
</script>

<style scoped>
.sidebar {
  font-family: 'Inria Sans', sans-serif;
  width: 280px;
  background: white;
  height: calc(100vh - 80px); /* Full height minus navbar height */
  position: fixed;
  top: 80px;
  left: 0;
  padding: 2.5rem 1.5rem;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  border-right: 1px solid #e2e8f0;
  
  /* Animasi Buka Tutup */
  transition: transform 0.3s ease-in-out;
  z-index: 40;
}

/* Kelas ketika sidebar tertutup */
.sidebar.closed {
  transform: translateX(-100%); /* Geser ke kiri sampai hilang */
}

.menu-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.menu-item {
  display: flex;
  align-items: center;
  gap: 1.2rem;
  padding: 1rem 1.5rem;
  color: #64748b;
  text-decoration: none;
  border-radius: 12px;
  font-weight: 700;
  font-size: 1.1rem;
  transition: all 0.2s ease;
}

/* Hover Effect */
.menu-item:hover {
  background-color: #fff4e6; /* Oranye sangat muda */
  color: #F6983E;
}

/* Active State (Tombol Oranye #F6983E) */
.menu-item.active {
  background-color: #F6983E; 
  color: #fff;
  box-shadow: 0 4px 12px rgba(246, 152, 62, 0.4);
}

.sidebar-footer {
  padding-top: 1.5rem;
  border-top: 2px solid #f1f5f9;
}

.faq-link {
  display: flex;
  align-items: center;
  gap: 1rem;
  color: #64748b;
  text-decoration: none;
  font-weight: 700;
  font-size: 1.1rem;
  padding: 0.5rem 1rem;
  transition: color 0.3s;
}
.faq-link:hover { color: #F6983E; }
</style>