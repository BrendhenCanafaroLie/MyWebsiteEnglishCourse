// kursus.js — SpeakUp English
// Filter buttons dengan logika show/hide kartu kursus

const filterMap = {
  'Semua':       null,
  'Pemula':      'level-beginner',
  'Menengah':    'level-intermediate',
  'Lanjutan':    'level-advanced',
  'Sertifikasi': 'level-advanced', // IELTS pakai level-advanced, sesuaikan jika ada class sendiri
};

document.querySelectorAll('.filter-btn').forEach(function (btn) {
  btn.addEventListener('click', function () {
    // Update tombol aktif
    document.querySelectorAll('.filter-btn').forEach(function (b) {
      b.classList.remove('active');
    });
    btn.classList.add('active');

    const label = btn.textContent.trim();
    const targetClass = filterMap[label];

    // Filter kartu
    document.querySelectorAll('.course-card').forEach(function (card) {
      if (!targetClass) {
        // "Semua" → tampilkan semua
        card.style.display = '';
      } else {
        const levelEl = card.querySelector('.course-level');
        if (levelEl && levelEl.classList.contains(targetClass)) {
          card.style.display = '';
        } else {
          card.style.display = 'none';
        }
      }
    });
  });
});
