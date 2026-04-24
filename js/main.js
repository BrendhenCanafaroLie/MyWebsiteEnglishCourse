// main.js — SpeakUp English
// Shared JavaScript: navbar toggle (semua halaman)

function toggleNav() {
  document.getElementById('navLinks').classList.toggle('open');
  document.body.classList.toggle('nav-open');
}

// Klik di luar menu = tutup
document.addEventListener('click', function (e) {
  const nav = document.getElementById('navLinks');
  const hamburger = document.querySelector('.hamburger');
  if (!nav.contains(e.target) && !hamburger.contains(e.target)) {
    nav.classList.remove('open');
    document.body.classList.remove('nav-open');
  }
});


// Animasi ringan untuk why-card
document.addEventListener('DOMContentLoaded', function () {
  const whyCards = document.querySelectorAll('.why-card');
  if (!whyCards.length) return;

  const observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('show');
      }
    });
  }, { threshold: 0.2 });

  whyCards.forEach(function (card) {
    observer.observe(card);
  });
});


// Validasi form pendaftaran (hanya untuk halaman daftar.html)
function handleDaftar() {
  const nama  = document.querySelector('input[type="text"]').value.trim();
  const email = document.querySelector('input[type="email"]').value.trim();
  const wa    = document.querySelector('input[type="tel"]').value.trim();

  // Cek field wajib
  if (!nama || !email || !wa) {
    alert("Harap isi semua data wajib!");
    return;
  }

  // Validasi format email
  const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  if (!emailValid) {
    alert("Format email tidak valid!");
    return;
  }

  // Validasi format nomor WA Indonesia
  const waValid = /^(\+62|62|0)[0-9]{8,12}$/.test(wa.replace(/[\s-]/g, ''));
  if (!waValid) {
    alert("Format nomor WhatsApp tidak valid! Contoh: 08xx atau +62xx");
    return;
  }

  document.getElementById('successMsg').style.display = 'block';
}