<?php
// kontak.php — Halaman Kontak (Checkpoint 3)
session_start();
require_once __DIR__ . '/php/config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Kontak — SpeakUp English</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/global.css">
  <link rel="stylesheet" href="css/kontak.css">
</head>
<body>

  <nav>
    <a href="index.php" class="logo">Speak<span>Up</span></a>
    <ul class="nav-links" id="navLinks">
      <li><a href="index.php">Beranda</a></li>
      <li><a href="kursus.php">Kursus</a></li>
      <li><a href="tentang.php">Tentang Kami</a></li>
      <li><a href="kontak.php" class="active">Kontak</a></li>
      <li><a href="daftar.php" class="nav-cta">Daftar Sekarang</a></li>
      <?php if (isAdmin()): ?>
      <li><a href="admin/dashboard.php" class="nav-admin-pill">⚙️ Admin</a></li>
      <?php elseif (isLoggedIn()): ?>
      <li><a href="riwayat.php" class="nav-login-pill">📋 Riwayat</a></li>
      <li><a href="logout.php" class="nav-logout-pill">Logout</a></li>
      <?php else: ?>
      <li><a href="login.php" class="nav-login-pill">Login</a></li>
      <?php endif; ?>
    </ul>
    <div class="hamburger" id="hamburger" onclick="toggleNav()">
      <span></span><span></span><span></span>
    </div>
  </nav>

  <div class="page-header">
    <div class="breadcrumb"><a href="index.php">Beranda</a> / Kontak</div>
    <div class="section-label">Hubungi Kami</div>
    <h1>Ada Pertanyaan?</h1>
    <p>Tim kami siap membantu kamu memilih program yang tepat dan menjawab semua pertanyaanmu.</p>
  </div>

  <section class="contact-section">
    <div class="contact-wrapper">

      <div class="contact-info">
        <h2>Kami Senang Mendengar dari Kamu</h2>
        <p>Jangan ragu untuk menghubungi kami melalui form, WhatsApp, email, atau langsung kunjungi kantor kami di Samarinda.</p>
        <div class="info-item">
          <div class="info-icon">📍</div>
          <div class="info-content"><h4>Alamat Kantor</h4><p>Jl. XXXXX No. XX, Samarinda<br>Kalimantan Timur, 75117</p></div>
        </div>
        <div class="info-item">
          <div class="info-icon">📞</div>
          <div class="info-content"><h4>Telepon / WhatsApp</h4><p>+62 xxx-xxxx-xxx<br>Senin – Jumat, 08.00 – 17.00 WITA</p></div>
        </div>
        <div class="info-item">
          <div class="info-icon">✉️</div>
          <div class="info-content"><h4>Email</h4><p>info@speakupenglish.id<br>support@speakupenglish.id</p></div>
        </div>
        <div class="social-links">
          <a href="#" class="social-btn" title="Instagram">📷</a>
          <a href="#" class="social-btn" title="TikTok">🎵</a>
          <a href="#" class="social-btn" title="YouTube">▶️</a>
          <a href="#" class="social-btn" title="WhatsApp">💬</a>
        </div>
      </div>

      <div class="contact-form">
        <h3>Kirim Pesan</h3>
        <p class="subtitle">Isi formulir di bawah dan tim kami akan segera merespons.</p>

        <div class="form-row">
          <div class="form-group">
            <label>Nama Lengkap *</label>
            <input type="text" id="k-nama" placeholder="Masukkan nama lengkap"/>
          </div>
          <div class="form-group">
            <label>Email *</label>
            <input type="email" id="k-email" placeholder="nama@email.com"/>
          </div>
        </div>
        <div class="form-group">
          <label>Nomor WhatsApp</label>
          <input type="tel" id="k-wa" placeholder="+62 xxx-xxxx-xxxx"/>
        </div>
        <div class="form-group">
          <label>Topik</label>
          <select id="k-topik">
            <option value="">— Pilih Topik —</option>
            <option>Informasi Kursus</option>
            <option>Pendaftaran</option>
            <option>Pembayaran</option>
            <option>Teknis / Akses Platform</option>
            <option>Lainnya</option>
          </select>
        </div>
        <div class="form-group full">
          <label>Pesan *</label>
          <textarea id="k-pesan" placeholder="Tuliskan pertanyaan atau pesan kamu di sini..."></textarea>
        </div>
        <button class="btn-submit" id="btnKirim" onclick="handleSubmit()">Kirim Pesan →</button>
        <div class="success-msg" id="successMsg" style="display:none">✅ Pesan berhasil dikirim! Tim kami akan menghubungi kamu segera.</div>
      </div>

    </div>
  </section>

  <div class="map-section">
    <div class="map-box">
      <div class="map-icon">🗺️</div>
      <strong>SpeakUp English — Samarinda</strong>
      <p>Jl. XXXXX No. XX, Samarinda, Kalimantan Timur</p>
    </div>
  </div>

  <footer>
    <div class="footer-grid">
      <div class="footer-brand">
        <a href="index.php" class="logo">Speak<span>Up</span></a>
        <p>Platform kursus bahasa Inggris online yang membantu Anda mencapai potensi terbaik dalam berkomunikasi secara global.</p>
      </div>
      <div class="footer-col">
        <h4>Navigasi</h4>
        <ul>
          <li><a href="index.php">Beranda</a></li>
          <li><a href="kursus.php">Kursus</a></li>
          <li><a href="tentang.php">Tentang Kami</a></li>
          <li><a href="kontak.php">Kontak</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2025 SpeakUp English. Dibuat dengan ❤ untuk pelajar Indonesia.</span>
      <span>Samarinda, Kalimantan Timur</span>
    </div>
  </footer>

  <script src="js/main.js"></script>
  <script src="js/kontak.js"></script>
  <script src="js/animations.js"></script>
</body>
</html>
