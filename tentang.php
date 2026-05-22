<?php
session_start();
require_once __DIR__ . '/php/config.php';
$db    = getDB();
$stats = $db->query("SELECT SUM(siswa) AS total_siswa, COUNT(*) AS total_kursus FROM courses")->fetch();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Tentang Kami — SpeakUp English</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/global.css">
  <link rel="stylesheet" href="css/tentang.css">
</head>
<body>

  <!-- NAVBAR -->
  <nav>
    <a href="index.php" class="logo">Speak<span>Up</span></a>
    <ul class="nav-links" id="navLinks">
      <li><a href="index.php">Beranda</a></li>
      <li><a href="kursus.php">Kursus</a></li>
      <li><a href="tentang.php" class="active">Tentang Kami</a></li>
      <li><a href="kontak.php">Kontak</a></li>
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

  <!-- PAGE HEADER -->
  <div class="page-header">
    <div class="breadcrumb"><a href="index.php">Beranda</a> / Tentang Kami</div>
    <div class="section-label">Siapa Kami</div>
    <h1>Tentang SpeakUp English</h1>
    <p>Platform kursus bahasa Inggris online yang didirikan di Samarinda, berkomitmen menghadirkan pendidikan berkualitas untuk semua.</p>
  </div>

  <!-- STORY — statistik diambil dari database -->
  <section>
    <div class="about-story">
      <div class="about-text">
        <div class="section-label">Cerita Kami</div>
        <h2>Bermula dari Satu Mimpi Besar</h2>
        <p>SpeakUp English didirikan pada tahun 2020 oleh sekelompok pengajar dan profesional yang percaya bahwa kemampuan berbahasa Inggris yang baik bisa membuka pintu ke peluang-peluang luar biasa.</p>
        <p>Berawal dari kelas kecil di Samarinda dengan hanya 12 siswa, kini kami telah berkembang menjadi platform digital yang melayani lebih dari <?= number_format($stats['total_siswa']) ?> siswa aktif dari seluruh Indonesia.</p>
        <p>Kami percaya bahwa keterbatasan akses tidak seharusnya menjadi penghalang seseorang untuk belajar bahasa Inggris. Itulah mengapa kami menghadirkan kurikulum berkualitas tinggi dengan harga yang terjangkau.</p>
      </div>
      <div class="about-visual">
        <div class="stat-box"><div class="num"><?= number_format($stats['total_siswa']) ?><span>+</span></div><div class="lbl">Siswa Aktif</div></div>
        <div class="stat-box"><div class="num">15<span>+</span></div><div class="lbl">Instruktur</div></div>
        <div class="stat-box"><div class="num">98<span>%</span></div><div class="lbl">Kepuasan</div></div>
        <div class="stat-box"><div class="num"><?= $stats['total_kursus'] ?><span>★</span></div><div class="lbl">Program Kursus</div></div>
      </div>
    </div>
  </section>

  <!-- MISSION -->
  <section class="mission-section">
    <div class="section-label">Visi &amp; Misi</div>
    <h2 class="section-heading">Tujuan Kami</h2>
    <p class="section-sub">Kami hadir dengan tujuan yang jelas dan nilai yang kuat untuk mendukung setiap perjalanan belajarmu.</p>
    <div class="mission-grid">
      <div class="mission-card">
        <div class="mission-icon">🔭</div>
        <h3>Visi</h3>
        <p>Menjadi platform kursus bahasa Inggris terpercaya dan terjangkau yang menjangkau seluruh pelosok Indonesia.</p>
      </div>
      <div class="mission-card">
        <div class="mission-icon">🎯</div>
        <h3>Misi</h3>
        <p>Menghadirkan pendidikan bahasa Inggris berkualitas tinggi dengan teknologi modern dan instruktur berpengalaman.</p>
      </div>
      <div class="mission-card">
        <div class="mission-icon">💡</div>
        <h3>Inovasi</h3>
        <p>Terus mengembangkan metode pengajaran yang inovatif, menyenangkan, dan efektif untuk setiap gaya belajar.</p>
      </div>
    </div>
  </section>

  <!-- TEAM -->
  <section>
    <div class="section-label">Tim Kami</div>
    <h2 class="section-heading">Para Instruktur Terbaik</h2>
    <p class="section-sub">Dibimbing oleh para profesional berpengalaman yang berdedikasi untuk kesuksesanmu.</p>
    <div class="team-grid">
      <div class="team-card">
        <div class="team-avatar">RA</div>
        <h4>Rika Andini, M.Pd</h4>
        <div class="team-role">Kepala Instruktur</div>
        <p class="team-bio">Berpengalaman 8 tahun, IELTS 8.0, lulusan S2 Pendidikan Bahasa Inggris UNLAM.</p>
      </div>
      <div class="team-card">
        <div class="team-avatar">BH</div>
        <h4>Budi Hartono, S.S</h4>
        <div class="team-role">Business English Coach</div>
        <p class="team-bio">Mantan eksekutif perusahaan multinasional, kini berdedikasi mengajar Business English.</p>
      </div>
      <div class="team-card">
        <div class="team-avatar">DP</div>
        <h4>Diana Putri, M.Hum</h4>
        <div class="team-role">IELTS Specialist</div>
        <p class="team-bio">Bersertifikat Cambridge CELTA, spesialis persiapan IELTS &amp; TOEFL dengan 200+ alumni sukses.</p>
      </div>
      <div class="team-card">
        <div class="team-avatar">YS</div>
        <h4>Yusuf Santoso, S.Pd</h4>
        <div class="team-role">Conversation Coach</div>
        <p class="team-bio">Berpengalaman mengajar di luar negeri, ahli dalam teknik public speaking dan conversational English.</p>
      </div>
    </div>
  </section>

  <!-- VALUES -->
  <section class="values-section">
    <div class="section-label">Nilai Kami</div>
    <h2 class="section-heading">Yang Kami Pegang Teguh</h2>
    <p class="section-sub">Nilai-nilai ini menjadi fondasi semua yang kami lakukan di SpeakUp English.</p>
    <div class="values-grid">
      <div class="value-item">
        <div class="value-icon">🏆</div>
        <div class="value-text">
          <h4>Kualitas Terdepan</h4>
          <p>Kami tidak berkompromi soal kualitas materi, instruktur, dan layanan yang kami berikan kepada siswa.</p>
        </div>
      </div>
      <div class="value-item">
        <div class="value-icon">❤️</div>
        <div class="value-text">
          <h4>Empati &amp; Inklusivitas</h4>
          <p>Setiap siswa berbeda. Kami menyesuaikan pendekatan agar semua orang bisa belajar dengan nyaman.</p>
        </div>
      </div>
      <div class="value-item">
        <div class="value-icon">🚀</div>
        <div class="value-text">
          <h4>Inovasi Berkelanjutan</h4>
          <p>Kami terus memperbarui kurikulum dan metode pengajaran mengikuti perkembangan zaman.</p>
        </div>
      </div>
      <div class="value-item">
        <div class="value-icon">🤝</div>
        <div class="value-text">
          <h4>Komunitas &amp; Kolaborasi</h4>
          <p>Belajar lebih bermakna bersama komunitas yang saling mendukung dan mendorong satu sama lain.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
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
      <div class="footer-col">
        <h4>Program</h4>
        <ul>
          <li><a href="detail.php?slug=english-for-beginners">English for Beginners</a></li>
          <li><a href="detail.php?slug=conversational-english">Conversational English</a></li>
          <li><a href="detail.php?slug=business-english">Business English</a></li>
          <li><a href="detail.php?slug=ielts-preparation">IELTS Preparation</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2025 SpeakUp English. Dibuat dengan ❤ untuk pelajar Indonesia.</span>
      <span>Samarinda, Kalimantan Timur</span>
    </div>
  </footer>

  <script src="js/main.js"></script>
  <script src="js/animations.js"></script>
</body>
</html>