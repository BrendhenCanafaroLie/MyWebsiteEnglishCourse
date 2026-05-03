<?php
// ============================================================
// kursus.php — Halaman Daftar Kursus
// PHP: render semua kursus dari DB (server-side)
// JS : pencarian real-time + filter level (client-side)
//      + Simpan Favorit ke LocalStorage
// ============================================================
require_once __DIR__ . '/php/config.php';

$db  = getDB();
$stmt = $db->query("SELECT * FROM courses ORDER BY id ASC");
$all_courses = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Kursus — SpeakUp English</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/global.css">
  <link rel="stylesheet" href="css/kursus.css">
</head>
<body>

  <!-- NAVBAR -->
  <nav>
    <a href="index.php" class="logo">Speak<span>Up</span></a>
    <ul class="nav-links" id="navLinks">
      <li><a href="index.php">Beranda</a></li>
      <li><a href="kursus.php" class="active">Kursus</a></li>
      <li><a href="tentang.php">Tentang Kami</a></li>
      <li><a href="kontak.php">Kontak</a></li>
      <li><a href="daftar.php" class="nav-cta">Daftar Sekarang</a></li>
    </ul>
    <div class="hamburger" id="hamburger" onclick="toggleNav()">
      <span></span><span></span><span></span>
    </div>
  </nav>

  <!-- PAGE HEADER -->
  <div class="page-header">
    <div class="breadcrumb"><a href="index.php">Beranda</a> / Kursus</div>
    <div class="section-label">Program Belajar</div>
    <h1>Semua Program Kursus</h1>
    <p>Temukan program kursus yang sesuai dengan level dan tujuanmu. Dari pemula hingga persiapan ujian internasional.</p>
  </div>

  <!-- ===== SEARCH BAR (DOM Events — input) ===== -->
  <div class="search-bar-wrap">
    <div class="search-bar">
      <span class="search-icon">🔍</span>
      <input
        type="text"
        id="searchInput"
        placeholder="Cari kursus... (contoh: business, IELTS, pemula)"
        autocomplete="off"
      />
      <button class="search-clear" id="clearSearch" style="display:none" title="Hapus pencarian">✕</button>
    </div>
    <div class="search-info" id="searchInfo"></div>
  </div>

  <!-- FILTER BAR (DOM Events — click) -->
  <div class="filter-bar">
    <button class="filter-btn active" data-level="Semua">Semua</button>
    <button class="filter-btn" data-level="Pemula">Pemula</button>
    <button class="filter-btn" data-level="Menengah">Menengah</button>
    <button class="filter-btn" data-level="Lanjutan">Lanjutan</button>
    <button class="filter-btn" data-level="Sertifikasi">Sertifikasi</button>
  </div>

  <!-- FAVORIT PANEL (LocalStorage) -->
  <div class="fav-panel" id="favPanel" style="display:none">
    <div class="fav-panel-header">
      <span>❤️ Kursus Favoritmu (<span id="favCount">0</span>)</span>
      <button onclick="toggleFavPanel()" class="fav-panel-close">Tutup ✕</button>
    </div>
    <div class="fav-panel-list" id="favList"></div>
  </div>

  <!-- COURSES SECTION -->
  <section class="courses-section">

    <!-- Promo Banner -->
    <div class="promo-banner">
      <div class="promo-text">
        <h3>🎉 Promo Pendaftaran Baru — Diskon 30%!</h3>
        <p>Berlaku hingga 31 Juli 2025. Daftar sekarang dan mulai belajar hari ini.</p>
      </div>
      <a href="daftar.php" class="btn-promo">Klaim Diskon</a>
    </div>

    <!-- No result message -->
    <div class="no-result" id="noResult" style="display:none">
      <div class="no-result-icon">🔍</div>
      <p>Kursus tidak ditemukan. Coba kata kunci lain.</p>
    </div>

    <!-- Kursus Grid — di-render PHP dari database, filter oleh JS -->
    <div class="courses-grid" id="coursesGrid">

      <?php foreach ($all_courses as $kursus): ?>
      <div class="course-card"
           data-slug="<?= htmlspecialchars($kursus['slug']) ?>"
           data-level="<?= htmlspecialchars($kursus['level']) ?>"
           data-nama="<?= htmlspecialchars(strtolower($kursus['nama'])) ?>"
           data-desc="<?= htmlspecialchars(strtolower($kursus['deskripsi'])) ?>">

        <div class="course-thumb <?= $kursus['thumb_class'] ?>"><?= $kursus['emoji'] ?></div>
        <div class="course-body">
          <!-- Tombol Favorit — LocalStorage -->
          <button
            class="fav-btn"
            data-id="<?= $kursus['id'] ?>"
            data-slug="<?= htmlspecialchars($kursus['slug']) ?>"
            data-nama="<?= htmlspecialchars($kursus['nama']) ?>"
            title="Simpan ke favorit"
            onclick="toggleFavorit(this)"
          >♡</button>

          <span class="course-level <?= levelClass($kursus['level']) ?>"><?= htmlspecialchars($kursus['level']) ?></span>
          <h3><?= htmlspecialchars($kursus['nama']) ?></h3>
          <p><?= htmlspecialchars($kursus['deskripsi']) ?></p>
          <div class="course-meta">
            <span>⏱ <?= htmlspecialchars($kursus['durasi']) ?></span>
            <span>📖 <?= $kursus['materi'] ?> Materi</span>
            <span>⭐ <?= $kursus['rating'] ?></span>
            <span>👥 <?= number_format($kursus['siswa']) ?> Siswa</span>
          </div>
          <div class="course-price">
            <div>
              <span class="price"><?= formatRupiah($kursus['harga']) ?></span>
              <span class="price-badge">/ kursus</span>
            </div>
            <a href="detail.php?slug=<?= htmlspecialchars($kursus['slug']) ?>" class="btn-enroll">Detail →</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>

    </div>
  </section>

  <!-- FAV FLOATING BUTTON -->
  <button class="fav-float-btn" onclick="toggleFavPanel()" title="Lihat Favorit">
    ❤️ <span id="favBadge" class="fav-badge" style="display:none">0</span>
  </button>

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
          <?php foreach ($all_courses as $c): ?>
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
  <script src="js/kursus.js"></script>
  <script src="js/animations.js"></script>
</body>
</html>
