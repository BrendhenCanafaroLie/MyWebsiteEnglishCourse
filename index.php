<?php
// ============================================================
// index.php — Halaman Utama SpeakUp English (Checkpoint 2)
// Menampilkan preview kursus dari database (server-side PHP)
// ============================================================
require_once __DIR__ . '/php/config.php';

$db = getDB();

// Ambil 3 kursus teratas untuk preview (hero)
$stmt = $db->query("SELECT * FROM courses ORDER BY siswa DESC LIMIT 3");
$preview_courses = $stmt->fetchAll();

// Statistik dinamis dari database
$stats = $db->query("SELECT SUM(siswa) AS total_siswa, COUNT(*) AS total_kursus, AVG(rating) AS avg_rating FROM courses")->fetch();
$total_siswa   = number_format($stats['total_siswa'] ?? 0, 0, ',', '.');
$total_kursus  = $stats['total_kursus'] ?? 0;
$avg_rating    = round($stats['avg_rating'] ?? 0, 1);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SpeakUp English — Kursus Bahasa Inggris Online</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/global.css">
  <link rel="stylesheet" href="css/index.css">
</head>
<body>

  <!-- NAVBAR -->
  <nav>
    <a href="index.php" class="logo">Speak<span>Up</span></a>
    <ul class="nav-links" id="navLinks">
      <li><a href="index.php" class="active">Beranda</a></li>
      <li><a href="kursus.php">Kursus</a></li>
      <li><a href="tentang.php">Tentang Kami</a></li>
      <li><a href="kontak.php">Kontak</a></li>
      <li><a href="daftar.php" class="nav-cta">Daftar Sekarang</a></li>
    </ul>
    <div class="hamburger" id="hamburger" onclick="toggleNav()">
      <span></span><span></span><span></span>
    </div>
  </nav>

  <!-- HERO -->
  <section class="hero">
    <div class="hero-content">
      <div class="hero-badge">Kursus Bahasa Inggris Online #1</div>
      <h1>Kuasai <em>Bahasa Inggris</em> dengan Percaya Diri</h1>
      <p>Belajar bahasa Inggris secara efektif bersama instruktur berpengalaman. Dari pemula hingga mahir — kami hadir untuk menemanimu di setiap langkah.</p>
      <div class="hero-btns">
        <a href="kursus.php" class="btn-primary">Lihat Program Kursus</a>
        <a href="tentang.php" class="btn-outline">Pelajari Lebih Lanjut</a>
      </div>
      <!-- Statistik diambil dari database -->
      <div class="hero-stats">
        <div class="stat-item">
          <div class="stat-num"><?= number_format($stats['total_siswa'], 0, ',', '.') ?><span>+</span></div>
          <div class="stat-label">Siswa Aktif</div>
        </div>
        <div class="stat-item">
          <div class="stat-num"><?= $total_kursus ?><span>+</span></div>
          <div class="stat-label">Program Kursus</div>
        </div>
        <div class="stat-item">
          <div class="stat-nums"><?= $avg_rating ?><span>/5</span></div>
          <div class="stat-label">Rating Rata-rata</div>
        </div>
      </div>
    </div>
  </section>

  <!-- FEATURES -->
  <section>
    <div class="section-label">Kenapa SpeakUp?</div>
    <div class="section-title">Belajar dengan Cara<br>yang Tepat</div>
    <p class="section-sub">Kami menawarkan pengalaman belajar yang menyenangkan, terstruktur, dan terbukti efektif untuk semua kalangan.</p>
    <div class="features-grid">
      <div class="feature-card">
        <div class="feature-icon">🎯</div>
        <h3>Kurikulum Terstruktur</h3>
        <p>Materi dirancang oleh para ahli linguistik dengan metode pembelajaran yang terbukti secara ilmiah.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">🌐</div>
        <h3>Belajar Online Fleksibel</h3>
        <p>Akses materi kapan saja, di mana saja. Sesuaikan jadwal belajar dengan aktivitas harianmu.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">👩‍🏫</div>
        <h3>Instruktur Berpengalaman</h3>
        <p>Dibimbing oleh instruktur bersertifikat TEFL/IELTS dengan pengalaman mengajar lebih dari 5 tahun.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">📜</div>
        <h3>Sertifikat Resmi</h3>
        <p>Dapatkan sertifikat yang diakui oleh ratusan perusahaan dan instansi pendidikan di Indonesia.</p>
      </div>
    </div>
  </section>

  <!-- COURSES PREVIEW — dari database -->
  <section class="courses-section">
    <div class="courses-header">
      <div>
        <div class="section-label">Program Unggulan</div>
        <div class="section-title">Pilih Kursus<br>Sesuai Levelmu</div>
      </div>
      <a href="kursus.php" class="btn-outline" style="white-space:nowrap;">Semua Kursus →</a>
    </div>
    <div class="courses-grid">
      <?php foreach ($preview_courses as $kursus): ?>
      <div class="course-card" onclick="location.href='detail.php?slug=<?= htmlspecialchars($kursus['slug']) ?>'">
        <div class="course-thumb <?= $kursus['thumb_class'] ?>"><?= $kursus['emoji'] ?></div>
        <div class="course-body">
          <span class="course-level <?= levelClass($kursus['level']) ?>"><?= htmlspecialchars($kursus['level']) ?></span>
          <h3><?= htmlspecialchars($kursus['nama']) ?></h3>
          <p><?= htmlspecialchars($kursus['deskripsi']) ?></p>
          <div class="course-meta">
            <span>⏱ <?= htmlspecialchars($kursus['durasi']) ?></span>
            <span>📖 <?= $kursus['materi'] ?> Materi</span>
            <span>⭐ <?= $kursus['rating'] ?></span>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- WHY CHOOSE US -->
  <section class="why-section">
    <div class="section-label">Kenapa Pilih Kami?</div>
    <div class="section-title">Belajar Bahasa Inggris<br>dengan Cara yang Lebih Nyaman</div>
    <p class="section-sub">SpeakUp dirancang untuk kamu yang ingin belajar bahasa Inggris tanpa tekanan, fleksibel, dan langsung bisa dipakai.</p>
    <div class="why-grid">
      <div class="why-card"><div class="why-icon">💬</div><h3>Fokus ke Speaking</h3><p>Bukan cuma hafalan grammar. Kamu akan lebih banyak latihan ngomong, menjawab, dan membangun percaya diri.</p></div>
      <div class="why-card"><div class="why-icon">⏰</div><h3>Jadwal Fleksibel</h3><p>Belajar bisa disesuaikan dengan rutinitasmu. Cocok untuk pelajar, mahasiswa, pekerja, atau yang sibuk.</p></div>
      <div class="why-card"><div class="why-icon">👩‍🏫</div><h3>Dibimbing Instruktur</h3><p>Kamu nggak belajar sendirian. Ada instruktur yang membantu, mengoreksi, dan memberi arahan selama proses belajar.</p></div>
      <div class="why-card"><div class="why-icon">📚</div><h3>Materi Mudah Dipahami</h3><p>Materi dibuat sederhana, bertahap, dan langsung relevan dengan percakapan sehari-hari maupun kebutuhan kerja.</p></div>
    </div>
  </section>

  <!-- CTA -->
  <div class="cta-section">
    <h2>Mulai Perjalanan Bahasa Inggrismu Hari Ini</h2>
    <p>Bergabunglah dengan lebih dari <?= number_format($stats['total_siswa']) ?> siswa aktif dan raih impianmu bersama SpeakUp English.</p>
    <a href="daftar.php" class="btn-primary">Daftar Gratis Sekarang</a>
  </div>

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
          <?php
          $all = $db->query("SELECT slug, nama FROM courses ORDER BY id LIMIT 4")->fetchAll();
          foreach ($all as $c): ?>
          <li><a href="detail.php?slug=<?= $c['slug'] ?>"><?= htmlspecialchars($c['nama']) ?></a></li>
          <?php endforeach; ?>
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
