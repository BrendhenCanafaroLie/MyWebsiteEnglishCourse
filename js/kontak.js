// js/kontak.js — SpeakUp English
// Submit form kontak ke api/kontak.php via fetch

async function handleSubmit() {
  const nama  = document.getElementById('k-nama')?.value.trim()  || '';
  const email = document.getElementById('k-email')?.value.trim() || '';
  const wa    = document.getElementById('k-wa')?.value.trim()    || '';
  const topik = document.getElementById('k-topik')?.value        || '';
  const pesan = document.getElementById('k-pesan')?.value.trim() || '';

  if (!nama || !email || !pesan) {
    alert('Harap isi Nama, Email, dan Pesan!');
    return;
  }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    alert('Format email tidak valid!');
    return;
  }

  const btn = document.getElementById('btnKirim');
  btn.disabled    = true;
  btn.textContent = 'Mengirim...';

  try {
    const res  = await fetch('api/kontak.php', {
      method:  'POST',
      headers: { 'Content-Type': 'application/json' },
      body:    JSON.stringify({ nama, email, whatsapp: wa, topik, pesan }),
    });
    const data = await res.json();

    if (data.status === 'ok') {
      document.getElementById('successMsg').style.display = 'block';
      setTimeout(() => document.getElementById('successMsg').style.display = 'none', 5000);
    } else {
      alert('Gagal: ' + (data.errors?.join(', ') || data.message));
    }
  } catch {
    alert('Gagal menghubungi server.');
  }

  btn.disabled    = false;
  btn.textContent = 'Kirim Pesan →';
}
