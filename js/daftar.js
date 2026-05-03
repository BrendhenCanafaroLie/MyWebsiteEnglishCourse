// ============================================================
// js/daftar.js — SpeakUp English
// Fitur:
//  - Validasi real-time (Event: input, blur)
//  - Submit ke API PHP (fetch POST)
//  - Simpan data terakhir ke LocalStorage (draft form)
// ============================================================

// ---- Auto-save draft ke LocalStorage ----
const DRAFT_KEY = 'speakup_daftar_draft';

function loadDraft() {
  try {
    const draft = JSON.parse(localStorage.getItem(DRAFT_KEY));
    if (!draft) return;
    if (draft.nama)  document.getElementById('nama').value  = draft.nama;
    if (draft.email) document.getElementById('email').value = draft.email;
    if (draft.wa)    document.getElementById('wa').value    = draft.wa;
    if (draft.tujuan) document.getElementById('tujuan').value = draft.tujuan;
  } catch {}
}

function saveDraft() {
  const draft = {
    nama:   document.getElementById('nama')?.value  || '',
    email:  document.getElementById('email')?.value || '',
    wa:     document.getElementById('wa')?.value    || '',
    tujuan: document.getElementById('tujuan')?.value || '',
  };
  localStorage.setItem(DRAFT_KEY, JSON.stringify(draft));
}

// ---- Validasi per-field ----
function setError(fieldId, msg) {
  const el = document.getElementById('err-' + fieldId);
  if (el) { el.textContent = msg; el.style.display = msg ? 'block' : 'none'; }
  const input = document.getElementById(fieldId);
  if (input) input.classList.toggle('input-error', !!msg);
}

function validateNama()  {
  const v = document.getElementById('nama').value.trim();
  setError('nama', v ? '' : 'Nama wajib diisi');
  return !!v;
}
function validateEmail() {
  const v = document.getElementById('email').value.trim();
  const ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
  setError('email', !v ? 'Email wajib diisi' : (!ok ? 'Format email tidak valid' : ''));
  return ok && !!v;
}
function validateWa() {
  const v = document.getElementById('wa').value.replace(/[\s\-]/g, '');
  const ok = /^(\+62|62|0)[0-9]{8,12}$/.test(v);
  setError('wa', !v ? 'Nomor WA wajib diisi' : (!ok ? 'Format nomor WA tidak valid (contoh: 08xx atau +62xx)' : ''));
  return ok && !!v;
}
function validateKursus() {
  const v = document.getElementById('kursus').value;
  setError('kursus', v ? '' : 'Pilih salah satu program kursus');
  return !!v;
}

// ---- Event listeners real-time ----
document.addEventListener('DOMContentLoaded', function () {
  loadDraft();

  document.getElementById('nama') ?.addEventListener('blur',  validateNama);
  document.getElementById('email')?.addEventListener('blur',  validateEmail);
  document.getElementById('wa')   ?.addEventListener('blur',  validateWa);
  document.getElementById('kursus')?.addEventListener('change', validateKursus);

  // Auto-save draft saat ada perubahan
  ['nama', 'email', 'wa', 'tujuan'].forEach(id => {
    document.getElementById(id)?.addEventListener('input', saveDraft);
  });
});

// ---- Submit ----
async function handleDaftar() {
  const ok = validateNama() & validateEmail() & validateWa() & validateKursus();
  if (!ok) return;

  const btn = document.getElementById('btnDaftar');
  btn.disabled    = true;
  btn.textContent = 'Memproses...';

  const body = {
    nama:      document.getElementById('nama').value.trim(),
    email:     document.getElementById('email').value.trim(),
    whatsapp:  document.getElementById('wa').value.trim(),
    kursus_id: document.getElementById('kursus').value,
    tujuan:    document.getElementById('tujuan').value.trim(),
  };

  try {
    const res  = await fetch('api/courses.php', {
      method:  'POST',
      headers: { 'Content-Type': 'application/json' },
      body:    JSON.stringify(body),
    });
    const data = await res.json();

    if (data.status === 'ok') {
      // Hapus draft setelah berhasil
      localStorage.removeItem(DRAFT_KEY);
      document.getElementById('successMsg').style.display = 'block';
      document.getElementById('successMsg').scrollIntoView({ behavior: 'smooth' });
      btn.textContent = 'Daftar Sekarang';
      btn.disabled    = false;
    } else {
      const msgs = data.errors?.join(', ') || data.message || 'Terjadi kesalahan';
      alert('Gagal: ' + msgs);
      btn.textContent = 'Daftar Sekarang';
      btn.disabled    = false;
    }
  } catch (err) {
    alert('Gagal menghubungi server. Periksa koneksi internet kamu.');
    btn.textContent = 'Daftar Sekarang';
    btn.disabled    = false;
  }
}
