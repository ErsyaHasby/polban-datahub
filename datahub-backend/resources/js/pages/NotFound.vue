<template>
  <div class="not-found-page" @mousemove="handleMouseMove">
    <div class="background-shapes">
      <div class="blob-container right-container" :style="containerRightStyle">
        <div class="blob shape-right"></div><div class="blob shape-right-layer"></div>
      </div>
      <div class="blob-container left-container" :style="containerLeftStyle">
        <div class="blob shape-left"></div><div class="blob shape-left-layer"></div>
      </div>
    </div>
    <div class="not-found-content" :style="contentParallax">
      <div class="error-code">404</div>
      <h1>Halaman Tidak Ditemukan</h1>
      <p>Maaf, halaman yang Anda tuju seperti data yang hilang—tidak dapat ditemukan.</p>
      <router-link to="/" class="btn-home">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
        Kembali ke Dashboard
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: 'NotFound',
  data() { return { mouseX: 0, mouseY: 0 } },
  computed: {
    containerRightStyle() { return { transform: `translate(${this.mouseX * -0.03}px, ${this.mouseY * -0.03}px)` } },
    containerLeftStyle() { return { transform: `translate(${this.mouseX * 0.05}px, ${this.mouseY * 0.05}px)` } },
    contentParallax() { return { transform: `translate(${this.mouseX * -0.01}px, ${this.mouseY * -0.01}px)` } }
  },
  methods: {
    handleMouseMove(e) { this.mouseX = e.clientX - window.innerWidth / 2; this.mouseY = e.clientY - window.innerHeight / 2; }
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inria+Sans:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap');

.not-found-page {
  font-family: 'Inria Sans', sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh;
  background: linear-gradient(135deg, #2148C0 0%, #172a75 100%); position: relative; overflow: hidden; color: white;
}
.background-shapes { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 0; pointer-events: none; }
.blob-container { position: absolute; transition: transform 0.2s ease-out; }
.right-container { top: -10%; right: -10%; width: 60vw; height: 60vw; }
.left-container { bottom: -15%; left: -15%; width: 50vw; height: 50vw; }
.blob { position: absolute; width: 100%; height: 100%; border-radius: 50%; animation: wobble 10s ease-in-out infinite alternate; }
.shape-right { background: linear-gradient(45deg, #1a359c, #2a52d6); opacity: 0.8; z-index: 2; }
.shape-right-layer { background: #1a359c; opacity: 0.3; transform: scale(1.1) rotate(20deg); animation: wobble 15s ease-in-out infinite alternate-reverse; z-index: 1; }
.shape-left { background: linear-gradient(to right, #152c7a, #1f40b0); opacity: 0.9; z-index: 2; animation: wobble 12s ease-in-out infinite alternate; }
.shape-left-layer { background: #152c7a; opacity: 0.4; transform: scale(1.2) rotate(-10deg); animation: wobble 18s ease-in-out infinite alternate-reverse; z-index: 1; }
@keyframes wobble { 0% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; transform: scale(1); } 50% { border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%; transform: scale(1.02) rotate(5deg); } 100% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; transform: scale(1); } }

.not-found-content { text-align: center; position: relative; z-index: 10; padding: 2rem; }
.error-code { font-size: 10rem; font-weight: 900; line-height: 1; color: transparent; -webkit-text-stroke: 3px rgba(255, 255, 255, 0.8); opacity: 0.8; margin-bottom: -1rem; }
h1 { font-size: 2.5rem; margin: 1rem 0; font-weight: 700; }
p { font-size: 1.2rem; margin: 0 auto 2.5rem auto; opacity: 0.9; max-width: 500px; font-weight: 300; }
.btn-home { display: inline-flex; align-items: center; gap: 0.5rem; padding: 1rem 2rem; background: white; color: #1B2376; text-decoration: none; border-radius: 50px; font-weight: 700; transition: all 0.3s; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
.btn-home:hover { transform: translateY(-3px); background-color: #F6983E; color: white; box-shadow: 0 10px 25px rgba(246, 152, 62, 0.4); }
</style>