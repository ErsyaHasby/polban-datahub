<template>
  <aside class="sidebar" :class="{ 'closed': !isOpen }">
    <div class="menu-list">
      
      <router-link 
        v-if="isAdmin" 
        to="/admin/review" 
        class="menu-item" 
        active-class="active" 
        :title="!isOpen ? 'Review Data' : ''"
      >
        <div class="icon-box">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        </div>
        <span class="menu-text">Review Data</span>
      </router-link>

      <router-link 
        v-if="isAdmin" 
        to="/admin/logs" 
        class="menu-item" 
        active-class="active" 
        :title="!isOpen ? 'Activity Logs' : ''"
      >
        <div class="icon-box">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
        </div>
        <span class="menu-text">Activity Logs</span>
      </router-link>

      <router-link 
        v-if="isAdmin"
        to="/download-data" 
        class="menu-item" 
        active-class="active" 
        :title="!isOpen ? 'Download Data' : ''"
      >
        <div class="icon-box">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
        </div>
        <span class="menu-text">Download Data</span>
      </router-link>

      <router-link 
        v-if="isParticipant" 
        to="/import-data" 
        class="menu-item" 
        active-class="active" 
        :title="!isOpen ? 'Import Data' : ''"
      >
        <div class="icon-box">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
        </div>
        <span class="menu-text">Import Data</span>
      </router-link>

      <router-link 
        v-if="isParticipant" 
        to="/my-uploads" 
        class="menu-item" 
        active-class="active" 
        :title="!isOpen ? 'Riwayat Upload' : ''"
      >
        <div class="icon-box">
           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
        </div>
        <span class="menu-text">Riwayat Upload</span>
      </router-link>

    </div>

    <div class="sidebar-footer">
      <div class="menu-item faq-item" :title="!isOpen ? 'Bantuan' : ''">
        <div class="icon-box">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
        </div>
        <span class="menu-text">Bantuan</span>
      </div>
    </div>
  </aside>
</template>

<script>
export default {
  name: 'Sidebar',
  props: { isAdmin: Boolean, isParticipant: Boolean, isOpen: Boolean }
}
</script>

<style scoped>
/* Sidebar Utama */
.sidebar {
  font-family: 'Inria Sans', sans-serif;
  width: 280px; 
  background: var(--surface); 
  height: calc(100vh - 90px); 
  position: fixed; top: 90px; left: 0;
  padding: 2rem 1.2rem;
  display: flex; flex-direction: column; justify-content: space-between;
  border-right: 1px solid rgba(0,0,0,0.05);
  box-shadow: 4px 0 20px rgba(0,0,0,0.02);
  transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), padding 0.3s ease;
  z-index: 40; overflow: hidden;
}

/* === MODE MINI (CLOSED) === */
.sidebar.closed {
  width: 90px; 
  padding: 2rem 0.8rem;
  transform: none; 
}

.menu-list { display: flex; flex-direction: column; gap: 0.8rem; }

/* Menu Item Default */
.menu-item {
  display: flex; align-items: center; gap: 1.2rem;
  padding: 1rem 1.5rem;
  color: #64748b; text-decoration: none;
  border-radius: 15px; font-weight: 700; font-size: 1rem;
  transition: all 0.3s ease; white-space: nowrap;
  border: 1px solid transparent;
}

/* Saat tertutup, ikon di tengah */
.sidebar.closed .menu-item { justify-content: center; padding: 1rem; }

.menu-text { transition: opacity 0.2s; opacity: 1; }
.sidebar.closed .menu-text { display: none; opacity: 0; }

.icon-box { display: flex; align-items: center; justify-content: center; min-width: 24px; font-size: 1.4rem; }

/* Hover Effect */
.menu-item:hover {
  background: #f8fafc; color: #1B2376;
  transform: translateX(3px);
}

/* === ACTIVE STATE MEWAH (GLOSSY) === */
.menu-item.active {
  background: linear-gradient(135deg, #FF914D 0%, #FF6B00 100%);
  color: white;
  box-shadow: 0 4px 15px rgba(255, 107, 0, 0.3); /* Orange Glow */
  border: 1px solid rgba(255,255,255,0.2);
}
.menu-item.active:hover { transform: none; }

.sidebar-footer { padding-top: 1rem; border-top: 1px solid #f1f5f9; }
.faq-item { color: #94a3b8; cursor: default; display: flex; align-items: center; gap: 1.2rem; padding: 1rem 1.5rem; border-radius: 15px; font-weight: 700; white-space: nowrap; }
.sidebar.closed .faq-item { justify-content: center; padding: 1rem; }
</style>