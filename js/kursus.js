// ============================================================
// js/kursus.js — SpeakUp English (Checkpoint 2)
//
// Fitur Client-Side:
//  1. Pencarian real-time (DOM Event: input)
//  2. Filter level (DOM Event: click)
//  3. Simpan Favorit ke LocalStorage
// ============================================================

// ---- 1. PENCARIAN REAL-TIME (input Event) ----
const searchInput = document.getElementById('searchInput');
const clearBtn    = document.getElementById('clearSearch');
const searchInfo  = document.getElementById('searchInfo');

searchInput.addEventListener('input', function () {
  const q = this.value.trim().toLowerCase();

  // Tampilkan/sembunyikan tombol clear
  clearBtn.style.display = q ? 'block' : 'none';

  applyFilters();
});

clearBtn.addEventListener('click', function () {
  searchInput.value = '';
  this.style.display = 'none';
  applyFilters();
});

// ---- 2. FILTER LEVEL (click Event) ----
document.querySelectorAll('.filter-btn').forEach(function (btn) {
  btn.addEventListener('click', function () {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    this.classList.add('active');
    applyFilters();
  });
});

// ---- LOGIC FILTER + SEARCH GABUNGAN ----
function applyFilters() {
  const q           = searchInput.value.trim().toLowerCase();
  const activeLevel = document.querySelector('.filter-btn.active')?.dataset.level || 'Semua';
  const cards       = document.querySelectorAll('.course-card');
  let   visible     = 0;

  cards.forEach(function (card) {
    const nama  = card.dataset.nama  || '';
    const desc  = card.dataset.desc  || '';
    const level = card.dataset.level || '';

    const matchSearch = !q || nama.includes(q) || desc.includes(q);
    const matchLevel  = activeLevel === 'Semua' || level === activeLevel;

    if (matchSearch && matchLevel) {
      card.style.display = '';
      card.classList.add('card-visible');
      visible++;
    } else {
      card.style.display = 'none';
      card.classList.remove('card-visible');
    }
  });

  // Info hasil pencarian
  if (q || activeLevel !== 'Semua') {
    searchInfo.textContent = `Menampilkan ${visible} kursus${q ? ' untuk "' + searchInput.value + '"' : ''}`;
    searchInfo.style.display = 'block';
  } else {
    searchInfo.style.display = 'none';
  }

  document.getElementById('noResult').style.display = visible === 0 ? 'block' : 'none';
}

// ============================================================
// 3. SIMPAN FAVORIT — LocalStorage
// Key: 'speakup_favorites' → Array of { id, slug, nama }
// ============================================================
const FAV_KEY = 'speakup_favorites';

function getFavorites() {
  try {
    return JSON.parse(localStorage.getItem(FAV_KEY)) || [];
  } catch {
    return [];
  }
}

function saveFavorites(favs) {
  localStorage.setItem(FAV_KEY, JSON.stringify(favs));
}

function isFavorit(id) {
  return getFavorites().some(f => String(f.id) === String(id));
}

// Toggle favorit dari tombol di kartu
function toggleFavorit(btn) {
  const id   = btn.dataset.id;
  const slug = btn.dataset.slug;
  const nama = btn.dataset.nama;
  let   favs = getFavorites();

  if (isFavorit(id)) {
    favs = favs.filter(f => String(f.id) !== String(id));
    btn.textContent = '♡';
    btn.classList.remove('fav-active');
    showToast('Dihapus dari favorit');
  } else {
    favs.push({ id, slug, nama });
    btn.textContent = '❤️';
    btn.classList.add('fav-active');
    showToast('Disimpan ke favorit!');
  }

  saveFavorites(favs);
  updateFavBadge();
  renderFavPanel();
}

function updateFavBadge() {
  const count = getFavorites().length;
  const badge = document.getElementById('favBadge');
  if (badge) {
    badge.textContent = count;
    badge.style.display = count > 0 ? 'inline-block' : 'none';
  }
}

function toggleFavPanel() {
  const panel = document.getElementById('favPanel');
  const isOpen = panel.style.display === 'block';
  panel.style.display = isOpen ? 'none' : 'block';
  if (!isOpen) renderFavPanel();
}

function renderFavPanel() {
  const favs     = getFavorites();
  const list     = document.getElementById('favList');
  const countEl  = document.getElementById('favCount');

  if (countEl) countEl.textContent = favs.length;

  if (!list) return;

  if (favs.length === 0) {
    list.innerHTML = '<p class="fav-empty">Belum ada kursus yang disimpan.</p>';
    return;
  }

  list.innerHTML = favs.map(f => `
    <div class="fav-item">
      <span>${f.nama}</span>
      <div class="fav-item-actions">
        <a href="detail.php?slug=${f.slug}" class="fav-link">Detail →</a>
        <button onclick="removeFav('${f.id}')" class="fav-remove">✕</button>
      </div>
    </div>
  `).join('');
}

function removeFav(id) {
  let favs = getFavorites().filter(f => String(f.id) !== String(id));
  saveFavorites(favs);
  updateFavBadge();
  renderFavPanel();
  syncFavButtons();
}

function syncFavButtons() {
  document.querySelectorAll('.fav-btn').forEach(btn => {
    const active = isFavorit(btn.dataset.id);
    btn.textContent = active ? '❤️' : '♡';
    btn.classList.toggle('fav-active', active);
  });
}

// Toast notification
function showToast(msg) {
  let toast = document.getElementById('favToast');
  if (!toast) {
    toast = document.createElement('div');
    toast.id = 'favToast';
    toast.className = 'fav-toast';
    document.body.appendChild(toast);
  }
  toast.textContent = msg;
  toast.classList.add('show');
  clearTimeout(toast._timer);
  toast._timer = setTimeout(() => toast.classList.remove('show'), 2500);
}

// ---- Init ----
document.addEventListener('DOMContentLoaded', function () {
  syncFavButtons();
  updateFavBadge();
});
