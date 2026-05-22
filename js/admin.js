// ============================================================
// js/admin.js — Admin Panel JavaScript
// ============================================================

// ---- Delete Modals ----

/**
 * Tampilkan modal konfirmasi hapus kursus
 */
function confirmDelete(id, nama) {
  document.getElementById('deleteId').value     = id;
  document.getElementById('deleteModalBody').textContent =
    `Kursus "${nama}" akan dihapus permanen. Yakin?`;
  showModal('deleteModal');
}

/**
 * Tampilkan modal konfirmasi hapus user
 */
function confirmDeleteUser(id, username) {
  document.getElementById('deleteUserId').value     = id;
  document.getElementById('deleteUserModalBody').textContent =
    `Akun user "${username}" akan dihapus. Yakin?`;
  showModal('deleteUserModal');
}

/**
 * Tampilkan modal konfirmasi hapus pendaftaran
 */
function confirmDeleteReg(id, nama) {
  document.getElementById('deleteRegId').value     = id;
  document.getElementById('deleteRegModalBody').textContent =
    `Data pendaftaran atas nama "${nama}" akan dihapus. Yakin?`;
  showModal('deleteRegModal');
}

function showModal(id) {
  const overlay = document.getElementById(id);
  if (overlay) overlay.style.display = 'flex';
}

function closeModal(id) {
  const overlay = id
    ? document.getElementById(id)
    : document.querySelector('.modal-overlay[style*="flex"]');
  if (overlay) overlay.style.display = 'none';
}

// Tutup modal dengan klik di luar
document.addEventListener('click', function (e) {
  if (e.target.classList.contains('modal-overlay')) {
    e.target.style.display = 'none';
  }
});

// Tutup modal dengan Escape
document.addEventListener('keydown', function (e) {
  if (e.key === 'Escape') {
    document.querySelectorAll('.modal-overlay').forEach(function (el) {
      el.style.display = 'none';
    });
  }
});

// ---- Table Real-time Search/Filter ----

/**
 * Filter baris tabel berdasarkan teks.
 * Default targetId: 'courseTable'
 */
function filterTable(query, tableId) {
  const tblId = tableId || 'courseTable';
  const table = document.getElementById(tblId);
  if (!table) return;

  const lq   = query.toLowerCase();
  const rows = table.querySelectorAll('tbody tr');
  let   found = 0;

  rows.forEach(function (row) {
    const text    = row.textContent.toLowerCase();
    const visible = !lq || text.includes(lq);
    row.style.display = visible ? '' : 'none';
    if (visible) found++;
  });

  // Tampilkan empty state jika tidak ada hasil
  const empty = table.closest('.admin-main')?.querySelector('.empty-state');
  if (empty) {
    empty.style.display = found === 0 ? 'block' : 'none';
  }
}

// ---- Slug auto-generator (form kursus) ----

/**
 * Otomatis generate slug dari nama kursus
 */
function autoSlug(nama) {
  const slugField   = document.getElementById('slugField');
  const slugPreview = document.getElementById('slugPreview');
  if (!slugField) return;

  // Hanya auto-fill jika slug field masih kosong
  if (slugField.dataset.manual === 'true') return;

  const slug = nama
    .toLowerCase()
    .replace(/[^a-z0-9\s-]/g, '')
    .replace(/[\s]+/g, '-')
    .replace(/-+/g, '-')
    .replace(/^-|-$/g, '');

  slugField.value = slug;
  if (slugPreview) slugPreview.textContent = slug || '...';
}

// Tandai slug sebagai manual jika user mengedit sendiri
document.addEventListener('DOMContentLoaded', function () {
  const slugField = document.getElementById('slugField');
  if (!slugField) return;

  slugField.addEventListener('input', function () {
    this.dataset.manual = 'true';
    const preview = document.getElementById('slugPreview');
    if (preview) preview.textContent = this.value || '...';
  });

  // Reset manual flag jika slug di-clear
  slugField.addEventListener('change', function () {
    if (!this.value) this.dataset.manual = 'false';
  });
});

// ---- Mobile Sidebar Toggle ----

function toggleAdminNav() {
  const nav     = document.getElementById('adminNav');
  const overlay = document.getElementById('adminOverlay');
  if (!nav) return;

  const isOpen = nav.classList.contains('open');
  if (isOpen) {
    closeAdminNav();
  } else {
    nav.classList.add('open');
    overlay?.classList.add('open');
    document.body.style.overflow = 'hidden';
  }
}

function closeAdminNav() {
  const nav     = document.getElementById('adminNav');
  const overlay = document.getElementById('adminOverlay');
  nav?.classList.remove('open');
  overlay?.classList.remove('open');
  document.body.style.overflow = '';
}

// Tutup sidebar saat resize ke desktop
window.addEventListener('resize', function () {
  if (window.innerWidth > 900) closeAdminNav();
});
