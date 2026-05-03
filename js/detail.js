// ============================================================
// js/detail.js — SpeakUp English
// Fitur: Simpan Favorit dari halaman detail (LocalStorage)
// ============================================================

const FAV_KEY = 'speakup_favorites';

function getFavorites() {
  try { return JSON.parse(localStorage.getItem(FAV_KEY)) || []; }
  catch { return []; }
}
function saveFavorites(favs) {
  localStorage.setItem(FAV_KEY, JSON.stringify(favs));
}

function toggleFavDetail() {
  const btn  = document.getElementById('favBtnDetail');
  if (!btn) return;

  const id   = btn.dataset.id;
  const slug = btn.dataset.slug;
  const nama = btn.dataset.nama;
  let   favs = getFavorites();

  if (favs.some(f => String(f.id) === String(id))) {
    favs = favs.filter(f => String(f.id) !== String(id));
    btn.innerHTML = '♡ Simpan Favorit';
    btn.classList.remove('fav-active');
  } else {
    favs.push({ id, slug, nama });
    btn.innerHTML = '❤️ Tersimpan!';
    btn.classList.add('fav-active');
  }
  saveFavorites(favs);
}

// Init: set status button saat halaman dimuat
document.addEventListener('DOMContentLoaded', function () {
  const btn = document.getElementById('favBtnDetail');
  if (!btn) return;
  const favs = getFavorites();
  if (favs.some(f => String(f.id) === btn.dataset.id)) {
    btn.innerHTML = '❤️ Tersimpan!';
    btn.classList.add('fav-active');
  }
});
