// kontak.js — SpeakUp English
// Form submission handler untuk halaman Kontak

function handleSubmit() {
  var nama  = document.querySelector('input[type="text"]').value.trim();
  var email = document.querySelector('input[type="email"]').value.trim();
  var pesan = document.querySelector('textarea').value.trim();

  if (!nama || !email || !pesan) {
    alert('Nama, email, dan pesan wajib diisi!');
    return;
  }

  var msg = document.getElementById('successMsg');
  msg.style.display = 'block';

  setTimeout(function () {
    msg.style.display = 'none';
  }, 4000);
}
