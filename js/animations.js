// ============================================================
// animations.js — SpeakUp English
// 1. Fade + slide up saat scroll (IntersectionObserver)
// 2. Count up angka statistik
// 3. Efek hover keren di kartu kursus
// ============================================================

// ============================================================
// 1. SCROLL REVEAL — elemen muncul saat di-scroll
// ============================================================
const revealElements = document.querySelectorAll(
  ".course-card, .feature-card, .why-card, .mission-card, " +
    ".team-card, .value-item, .stat-box, .detail-hero, " +
    ".promo-banner, .contact-form, .contact-info, .daftar-box",
);

// Tambahkan style awal (tersembunyi)
revealElements.forEach((el, i) => {
  el.style.opacity = "0";
  el.style.transform = "translateY(32px)";
  el.style.transition = `opacity 0.6s ease ${(i % 4) * 0.1}s, transform 0.6s ease ${(i % 4) * 0.1}s`;
});

const revealObserver = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = "1";
        entry.target.style.transform = "translateY(0)";
        revealObserver.unobserve(entry.target); // Animasi cukup sekali
      }
    });
  },
  { threshold: 0.12 },
);

revealElements.forEach((el) => revealObserver.observe(el));

// ============================================================
// 2. COUNT UP — angka statistik naik perlahan
// Target: elemen dengan class .num, .stat-num, .astat-val, .dstat-val
// ============================================================
function countUp(el) {
  // Ambil angka dari teks, abaikan karakter non-angka
  const raw = el.textContent.replace(/[^0-9]/g, "");
  const target = parseInt(raw);
  if (!target || isNaN(target)) return;

  // Simpan suffix asli ('+', '%', 'K+', '/5', dst)
  const suffix = el.innerHTML.replace(/[0-9,\.]/g, "").trim();
  const duration = 1800; // ms
  const steps = 60;
  const increment = target / steps;
  let current = 0;
  let step = 0;

  const timer = setInterval(() => {
    step++;
    current = Math.min(Math.round(increment * step), target);

    // Format angka ribuan
    const formatted =
      current >= 1000 ? current.toLocaleString("id-ID") : current;

    el.innerHTML = formatted + suffix;

    if (step >= steps) clearInterval(timer);
  }, duration / steps);
}

const countObserver = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        countUp(entry.target);
        countObserver.unobserve(entry.target);
      }
    });
  },
  { threshold: 0.5 },
);

document.querySelectorAll(".num, .stat-num, .dstat-val").forEach((el) => {
  // Hanya observe kalau ada angka
  if (/[0-9]/.test(el.textContent)) {
    countObserver.observe(el);
  }
});

// ============================================================
// 3. HOVER EFEK KEREN — kartu kursus
// Efek: cahaya/glow mengikuti posisi mouse di dalam kartu
// ============================================================
document
  .querySelectorAll(
    ".course-card, .mission-card, .team-card, .feature-card, .why-card",
  )
  .forEach((card) => {
    card.addEventListener("mousemove", function (e) {
      const rect = card.getBoundingClientRect();
      const x = ((e.clientX - rect.left) / rect.width) * 100;
      const y = ((e.clientY - rect.top) / rect.height) * 100;

      card.style.background = `
      radial-gradient(circle at ${x}% ${y}%,
        rgba(0, 180, 216, 0.07) 0%,
        transparent 60%),
      var(--card-bg, #142338)
    `;
    });

    card.addEventListener("mouseleave", function () {
      card.style.background = "";
      card.style.transform = "";
    });

    // Subtle tilt effect
    card.addEventListener("mousemove", function (e) {
      const rect = card.getBoundingClientRect();
      const centerX = rect.left + rect.width / 2;
      const centerY = rect.top + rect.height / 2;
      const rotateX = ((e.clientY - centerY) / rect.height) * -6;
      const rotateY = ((e.clientX - centerX) / rect.width) * 6;

      card.style.transform = `perspective(600px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-4px)`;
      card.style.transition = "transform 0.1s ease";
    });

    card.addEventListener("mouseleave", function () {
      card.style.transform = "";
      card.style.transition = "transform 0.4s ease";
    });
  });

// ============================================================
// 4. BONUS: Page load — hero section fade in
// ============================================================
const hero = document.querySelector(".hero, .page-header");
if (hero) {
  hero.style.opacity = "0";
  hero.style.transform = "translateY(20px)";
  hero.style.transition = "opacity 0.8s ease, transform 0.8s ease";
  requestAnimationFrame(() => {
    setTimeout(() => {
      hero.style.opacity = "1";
      hero.style.transform = "translateY(0)";
    }, 100);
  });
}
