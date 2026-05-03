<?php
// ============================================================
// detail.php — Halaman Detail Kursus
// URL: detail.php?slug=english-for-beginners
// PHP mengambil data spesifik berdasarkan slug dari database
// ============================================================
require_once __DIR__ . '/php/config.php';

$slug = trim($_GET['slug'] ?? '');

if (!$slug) {
    header('Location: kursus.php');
    exit;
}

$db   = getDB();
$stmt = $db->prepare("SELECT * FROM courses WHERE slug = ? LIMIT 1");
$stmt->execute([$slug]);
$kursus = $stmt->fetch();

if (!$kursus) {
    // 404 — kursus tidak ditemukan
    http_response_code(404);
    ?>
    <!DOCTYPE html>
    <html lang="id"><head><meta charset="UTF-8"><title>Tidak Ditemukan</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/global.css"></head>
    <body style="display:flex;align-items:center;justify-content:center;height:100vh;text-align:center">
      <div>
        <div style="font-size:5rem">🔍</div>
        <h2 style="margin:16px 0 8px">Kursus Tidak Ditemukan</h2>
        <p style="color:#8892a4">Slug "<strong><?= htmlspecialchars($slug) ?></strong>" tidak ada di database.</p>
        <a href="kursus.php" style="color:#00b4d8;margin-top:16px;display:inline-block">← Kembali ke Kursus</a>
      </div>
    </body></html>
    <?php
    exit;
}

// Kursus lain untuk rekomendasi
$rec_stmt = $db->prepare("SELECT id, slug, emoji, nama, level, harga, rating, thumb_class FROM courses WHERE id != ? ORDER BY siswa DESC LIMIT 3");
$rec_stmt->execute([$kursus['id']]);
$rekomendasi = $rec_stmt->fetchAll();

// Parse deskripsi_panjang jadi list item
$desc_lines = array_filter(explode("\n", $kursus['deskripsi_panjang'] ?? ''));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= htmlspecialchars($kursus['nama']) ?> — SpeakUp English</title>
  <meta name="description" content="<?= htmlspecialchars($kursus['deskripsi']) ?>"/>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/global.css">
  <link rel="stylesheet" href="css/detail.css">
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

  <!-- BREADCRUMB -->
  <div class="page-header">
    <div class="breadcrumb">
      <a href="index.php">Beranda</a> /
      <a href="kursus.php">Kursus</a> /
      <?= htmlspecialchars($kursus['nama']) ?>
    </div>
  </div>

  <!-- DETAIL HERO -->
  <section class="detail-hero">
    <div class="detail-thumb <?= $kursus['thumb_class'] ?>">
      <span class="detail-emoji"><?= $kursus['emoji'] ?></span>
    </div>
    <div class="detail-info">
      <span class="course-level <?= levelClass($kursus['level']) ?>"><?= htmlspecialchars($kursus['level']) ?></span>
      <h1><?= htmlspecialchars($kursus['nama']) ?></h1>
      <p class="detail-desc"><?= htmlspecialchars($kursus['deskripsi']) ?></p>

      <div class="detail-stats">
        <div class="dstat">
          <span class="dstat-val">⭐ <?= $kursus['rating'] ?></span>
          <span class="dstat-label">Rating</span>
        </div>
        <div class="dstat">
          <span class="dstat-val">👥 <?= number_format($kursus['siswa']) ?></span>
          <span class="dstat-label">Siswa</span>
        </div>
        <div class="dstat">
          <span class="dstat-val">⏱ <?= htmlspecialchars($kursus['durasi']) ?></span>
          <span class="dstat-label">Durasi</span>
        </div>
        <div class="dstat">
          <span class="dstat-val">📖 <?= $kursus['materi'] ?></span>
          <span class="dstat-label">Materi</span>
        </div>
      </div>

      <div class="detail-price-row">
        <div class="detail-price"><?= formatRupiah($kursus['harga']) ?></div>
        <a href="daftar.php?kursus=<?= urlencode($kursus['nama']) ?>" class="btn-primary">Daftar Kursus Ini</a>
        <!-- Tombol Favorit (LocalStorage) -->
        <button
          class="fav-btn-detail"
          id="favBtnDetail"
          data-id="<?= $kursus['id'] ?>"
          data-slug="<?= htmlspecialchars($kursus['slug']) ?>"
          data-nama="<?= htmlspecialchars($kursus['nama']) ?>"
          onclick="toggleFavDetail()"
        >♡ Simpan Favorit</button>
      </div>
    </div>
  </section>

  <!-- DESKRIPSI LENGKAP -->
  <section class="detail-body">
    <div class="detail-content">
      <h2>Tentang Kursus Ini</h2>
      <div class="detail-long-desc">
        <?php foreach ($desc_lines as $line):
          $line = trim($line);
          if (!$line) continue;
          if (str_starts_with($line, '•')) {
            echo '<div class="desc-bullet">' . htmlspecialchars(ltrim($line, '• ')) . '</div>';
          } else {
            echo '<p>' . htmlspecialchars($line) . '</p>';
          }
        endforeach; ?>
      </div>
    </div>

    <!-- SIDEBAR -->
    <div class="detail-sidebar">
      <div class="sidebar-card">
        <h3>Ringkasan Kursus</h3>
        <ul class="sidebar-list">
          <li><span>Level</span><strong><?= htmlspecialchars($kursus['level']) ?></strong></li>
          <li><span>Durasi</span><strong><?= htmlspecialchars($kursus['durasi']) ?></strong></li>
          <li><span>Jumlah Materi</span><strong><?= $kursus['materi'] ?> sesi</strong></li>
          <li><span>Rating</span><strong>⭐ <?= $kursus['rating'] ?>/5</strong></li>
          <li><span>Siswa</span><strong><?= number_format($kursus['siswa']) ?> orang</strong></li>
          <li><span>Harga</span><strong class="price-highlight"><?= formatRupiah($kursus['harga']) ?></strong></li>
        </ul>
        <a href="daftar.php?kursus=<?= urlencode($kursus['nama']) ?>" class="btn-primary" style="width:100%;text-align:center;display:block;margin-top:20px;">Daftar Sekarang</a>
        <a href="kontak.php" class="btn-outline" style="width:100%;text-align:center;display:block;margin-top:10px;">Tanya Dulu</a>
      </div>
    </div>
  </section>

  <!-- REKOMENDASI KURSUS LAIN -->
  <?php if ($rekomendasi): ?>
  <section class="rekomen-section">
    <div class="section-label">Kursus Lainnya</div>
    <div class="section-title" style="font-size:1.6rem;margin-bottom:24px">Mungkin Kamu Juga Suka</div>
    <div class="courses-grid">
      <?php foreach ($rekomendasi as $r): ?>
      <div class="course-card" onclick="location.href='detail.php?slug=<?= htmlspecialchars($r['slug']) ?>'">
        <div class="course-thumb <?= $r['thumb_class'] ?>"><?= $r['emoji'] ?></div>
        <div class="course-body">
          <span class="course-level <?= levelClass($r['level']) ?>"><?= htmlspecialchars($r['level']) ?></span>
          <h3><?= htmlspecialchars($r['nama']) ?></h3>
          <div class="course-meta">
            <span>⭐ <?= $r['rating'] ?></span>
            <span><?= formatRupiah($r['harga']) ?></span>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

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
    </div>
    <div class="footer-bottom">
      <span>© 2025 SpeakUp English. Dibuat dengan ❤ untuk pelajar Indonesia.</span>
      <span>Samarinda, Kalimantan Timur</span>
    </div>
  </footer>

  <script src="js/main.js"></script>
  <script src="js/detail.js"></script>
  <script src="js/animations.js"></script>
</body>
</html>
