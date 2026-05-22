// ============================================================
// js/auth.js — Login & Register client-side JS
// ============================================================

/**
 * Toggle visibility of password field
 */
function togglePassword(fieldId, btn) {
  const input = document.getElementById(fieldId);
  if (!input) return;

  const isHidden = input.type === 'password';
  input.type     = isHidden ? 'text' : 'password';
  btn.textContent = isHidden ? '🙈' : '👁';
  btn.title       = isHidden ? 'Sembunyikan password' : 'Tampilkan password';
}

// ---- Register: client-side real-time validation ----
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('registerForm');
  if (!form) return;

  const username  = document.getElementById('username');
  const email     = document.getElementById('email');
  const password  = document.getElementById('password');
  const password2 = document.getElementById('password2');

  function showError(id, msg) {
    const el = document.getElementById('err-' + id);
    if (!el) return;
    el.textContent = msg;
    el.style.display = msg ? 'block' : 'none';
  }

  username?.addEventListener('blur', function () {
    const v = this.value.trim();
    if (!v) return showError('username', 'Username wajib diisi.');
    if (v.length < 3 || v.length > 30) return showError('username', 'Username harus 3–30 karakter.');
    if (!/^[a-zA-Z0-9_]+$/.test(v)) return showError('username', 'Hanya huruf, angka, dan underscore.');
    showError('username', '');
  });

  email?.addEventListener('blur', function () {
    const v = this.value.trim();
    if (!v) return showError('email', 'Email wajib diisi.');
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) return showError('email', 'Format email tidak valid.');
    showError('email', '');
  });

  password?.addEventListener('blur', function () {
    const v = this.value;
    if (v.length < 6) return showError('password', 'Password minimal 6 karakter.');
    showError('password', '');
  });

  password2?.addEventListener('blur', function () {
    if (this.value !== password?.value) return showError('password2', 'Password tidak cocok.');
    showError('password2', '');
  });
});
