<template>
  <div class="custom-modal-backdrop" @click.self="$emit('close')">
    <div class="custom-modal-card">
      <div class="custom-modal-header">
        <slot name="header"><span>Info</span></slot>
        <button class="custom-modal-close" @click="$emit('close')">✕</button>
      </div>
      <div class="custom-modal-body">
        <slot />
      </div>
      <div class="custom-modal-footer">
        <slot name="footer">
          <button class="btn-primary" @click="$emit('close')">OK</button>
        </slot>
      </div>
    </div>
  </div>
</template>

<script>
export default { name: 'CustomModal' }
</script>

<style scoped>
.custom-modal-backdrop {
  position: fixed; inset: 0; background: rgba(27,35,118,0.25); display: flex; align-items: center; justify-content: center; z-index: 2000;
}
.custom-modal-card {
  background: #fff;
  color: #1B2376;
  border-radius: 16px;
  min-width: 320px;
  max-width: 95vw;
  min-height: 120px;
  box-shadow: 0 8px 40px rgba(27,35,118,0.15);
  padding: 0;
  display: flex; flex-direction: column;
  animation: modalPop 0.18s cubic-bezier(.4,2,.6,1) both;
}
@keyframes modalPop { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
.custom-modal-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 1.2rem 1.5rem 0.5rem 1.5rem;
  font-size: 1.2rem; font-weight: 700;
  border-bottom: 1px solid #f3f4f6;
}
.custom-modal-close {
  background: none; border: none; font-size: 1.5rem; color: #FF914D; cursor: pointer; font-weight: bold;
}
.custom-modal-body {
  padding: 1.2rem 1.5rem;
  font-size: 1rem;
}
.custom-modal-footer {
  padding: 1rem 1.5rem 1.2rem 1.5rem;
  display: flex; justify-content: flex-end; gap: 0.7rem;
  border-top: 1px solid #f3f4f6;
}
.btn-primary, .btn-danger, .btn-secondary {
  padding: 0.6rem 1.5rem; border-radius: 8px; font-weight: 700; border: none; cursor: pointer; font-size: 1rem;
}
.btn-primary { background: linear-gradient(90deg,#FF914D 0%,#FF6B00 100%); color: #fff; }
.btn-danger { background: #ef4444; color: #fff; }
.btn-secondary { background: #e5e7eb; color: #374151; }
.dark-theme .custom-modal-card { background: #181818; color: #FF914D; border: 1px solid #232323; }
.dark-theme .custom-modal-header, .dark-theme .custom-modal-footer { border-color: #232323; }
</style>
