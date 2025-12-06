<template>
  <footer class="main-footer">
    <div class="footer-content">
      
      <div class="footer-col brand-col">
        <div class="footer-brand">
          <img :src="'/images/logo.png'" alt="Logo" class="footer-logo" />
          <div>
            <h3>Polban DataHub</h3>
            <span>Satu Portal, Ribuan Cerita</span>
          </div>
        </div>
        <p class="brand-desc">
          Portal visualisasi data terintegrasi Politeknik Negeri Bandung.
        </p>
        <p class="copyright">© 2025 Polban Dataverse.</p>
      </div>

      <div class="footer-col">
        <h4>Menu</h4>
        <ul>
          <li><a href="#">Admin</a></li>
          <li><a href="#">Participant</a></li>
        </ul>
      </div>

      <div class="footer-col contact-col">
        <h4>Kontak</h4>
        <p>Jl. Gegerkalong Hilir, Ciwaruga, Bandung Barat 40559</p>
        <p>Email: polban@polban.ac.id</p>
      </div>

    </div>
  </footer>
</template>

<script>
export default { name: 'Footer' }
</script>

<style scoped>
.main-footer {
  font-family: 'Inria Sans', sans-serif;
  background-color: #1B2376; /* KEMBALIKAN BIRU UNTUK TERANG */
  color: white;
  /* REVISI: Padding diperkecil agar tidak menghabiskan tempat */
  padding: 1.5rem 3rem; 
  margin-top: auto; 
  font-size: 0.9rem; /* Font diperkecil */
}

/* Mode gelap: override dengan variabel */
.dark-theme .main-footer {
  background-color: var(--surface);
  color: var(--text);
}

.footer-content {
  max-width: 1400px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 1.5fr 0.5fr 1fr; 
  gap: 2rem;
  align-items: start;
}

.footer-brand {
  display: flex;
  align-items: center;
  gap: 0.8rem;
  margin-bottom: 0.5rem;
}

.footer-logo {
  height: 30px; /* Logo diperkecil */
  width: auto;
  background: white;
  padding: 3px;
  border-radius: 6px;
}

.footer-brand h3 { margin: 0; font-size: 1.1rem; font-weight: 700; }
.footer-brand span { font-size: 0.75rem; opacity: 0.8; display: block; }

.brand-desc {
  font-size: 0.85rem;
  opacity: 0.8;
  margin-bottom: 0.5rem;
  max-width: 350px;
  line-height: 1.4;
}

.copyright {
  font-size: 0.75rem;
  opacity: 0.5;
  margin-top: 0.5rem;
}

.footer-col h4 {
  font-size: 1rem;
  margin-bottom: 0.8rem;
  font-weight: 700;
  color: #FF914D; /* Aksen Oranye tetap */
}

.footer-col ul { list-style: none; padding: 0; margin: 0; }
.footer-col ul li { margin-bottom: 0.3rem; }

.footer-col a {
  color: rgba(255,255,255,0.8);
  text-decoration: none;
  font-size: 0.85rem;
}
.footer-col a:hover { color: #fff; text-decoration: underline; }

.contact-col p {
  margin: 0 0 0.3rem 0;
  font-size: 0.85rem;
  opacity: 0.9;
}
</style>