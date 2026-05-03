<?php
// ============================================================
// daftar.php — Halaman Pendaftaran
// PHP: ambil daftar kursus dari DB untuk dropdown
//       simpan data pendaftaran ke DB (via API)
// ============================================================
require_once __DIR__ . '/php/config.php';

$db      = getDB();
$courses = $db->query("SELECT id, nama, harga FROM courses ORDER BY id ASC")->fetchAll();

// Pre-select kursus dari query string (misal dari halaman detail)
$preselect = $_GET['kursus'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Daftar — SpeakUp English</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/global.css">
  <link rel="stylesheet" href="css/daftar.css">
</head>
<body>

  <nav>
    <a href="index.php" class="logo">Speak<span>Up</span></a>
    <ul class="nav-links" id="navLinks">
      <li><a href="index.php">Beranda</a></li>
      <li><a href="kursus.php">Kursus</a></li>
      <li><a href="tentang.php">Tentang Kami</a></li>
      <li><a href="kontak.php">Kontak</a></li>
      <li><a href="daftar.php" class="nav-cta active">Daftar Sekarang</a></li>
    </ul>
    <div class="hamburger" onclick="toggleNav()">
      <span></span><span></span><span></span>
    </div>
  </nav>

  <div class="page-header">
    <div class="breadcrumb"><a href="index.php">Beranda</a> / Daftar</div>
    <div class="section-label">Mulai Sekarang</div>
    <h1>Daftar Kursus</h1>
    <p>Isi data di bawah untuk mulai belajar bahasa Inggris bersama SpeakUp.</p>
  </div>

  <section class="daftar-section">
    <div class="daftar-box">
      <h2>Form Pendaftaran</h2>
      <p class="subtitle">Data kamu akan kami gunakan untuk menghubungi dan menentukan kelas yang cocok.</p>

      <!-- Nama -->
      <div class="form-group">
        <label for="nama">Nama Lengkap *</label>
        <input type="text" id="nama" placeholder="Masukkan nama lengkap" autocomplete="name"/>
        <div class="field-error" id="err-nama"></div>
      </div>

      <!-- Email -->
      <div class="form-group">
        <label for="email">Email *</label>
        <input type="email" id="email" placeholder="nama@email.com" autocomplete="email"/>
        <div class="field-error" id="err-email"></div>
      </div>

      <!-- WhatsApp -->
      <div class="form-group">
        <label for="wa">Nomor WhatsApp *</label>
        <input type="tel" id="wa" placeholder="+62 812-xxxx-xxxx" autocomplete="tel"/>
        <div class="field-error" id="err-wa"></div>
      </div>

      <!-- Pilih Kursus — dari database PHP -->
      <div class="form-group">
        <label for="kursus">Pilih Program *</label>
        <div class="select-wrapper">
          <select id="kursus">
            <option value="">— Pilih Kursus —</option>
            <?php foreach ($courses as $c): ?>
            <option
              value="<?= $c['id'] ?>"
              <?= ($preselect === $c['nama']) ? 'selected' : '' ?>
            >
              <?= htmlspecialchars($c['nama']) ?> — <?= formatRupiah($c['harga']) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="field-error" id="err-kursus"></div>
      </div>

      <!-- Tujuan -->
      <div class="form-group">
        <label for="tujuan">Tujuan Belajar</label>
        <textarea id="tujuan" placeholder="Contoh: untuk kerja, kuliah, atau traveling"></textarea>
      </div>

      <!-- Submit -->
      <button class="btn-submit" id="btnDaftar" onclick="handleDaftar()">Daftar Sekarang</button>

      <div class="success-msg" id="successMsg" style="display:none">
        ✅ Pendaftaran berhasil! Tim kami akan segera menghubungi kamu melalui WhatsApp atau email.
      </div>
    </div>
  </section>

  <script src="js/main.js"></script>
  <script src="js/daftar.js"></script>
  <script src="js/animations.js"></script>
</body>
</html>
