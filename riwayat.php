<?php
// ============================================================
// riwayat.php — Riwayat Pendaftaran Kursus (User)
// Hanya bisa diakses oleh user yang sudah login
// ============================================================
session_start();
require_once __DIR__ . '/php/config.php';
require_once APP_PATH . '/models/RegistrationModel.php';

// Guard: harus login
if (!isLoggedIn()) {
    setFlash('error', 'Silakan login terlebih dahulu untuk melihat riwayat pendaftaran.');
    redirect('login.php');
}

// Admin diarahkan ke dashboard
if (isAdmin()) {
    redirect('admin/registrations.php');
}

$userId    = (int) $_SESSION['user_id'];
$regModel  = new RegistrationModel();

$perPage  = 6;
$page     = max(1, (int) ($_GET['page'] ?? 1));
$offset   = ($page - 1) * $perPage;
$total    = $regModel->countByUserId($userId);
$pages    = (int) ceil($total / $perPage);
$riwayat  = $regModel->getByUserId($userId, $perPage, $offset);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Riwayat Pendaftaran — SpeakUp English</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/global.css">
  <link rel="stylesheet" href="css/riwayat.css">
</head>
<body>

  <!-- NAVBAR -->
  <nav>
    <a href="index.php" class="logo">Speak<span>Up</span></a>
    <ul class="nav-links" id="navLinks">
      <li><a href="index.php">Beranda</a></li>
      <li><a href="kursus.php">Kursus</a></li>
      <li><a href="tentang.php">Tentang Kami</a></li>
      <li><a href="kontak.php">Kontak</a></li>
      <li><a href="daftar.php" class="nav-cta">Daftar Sekarang</a></li>
      <li><a href="riwayat.php" class="nav-login-pill active">📋 Riwayat</a></li>
      <li><a href="logout.php" class="nav-logout-pill">Logout</a></li>
    </ul>
    <div class="hamburger" onclick="toggleNav()">
      <span></span><span></span><span></span>
    </div>
  </nav>

  <!-- Header -->
  <div class="page-header">
    <div class="breadcrumb"><a href="index.php">Beranda</a> / Riwayat</div>
    <div class="section-label">Akun Saya</div>
    <h1>Riwayat Pendaftaran</h1>
    <p>Halo, <strong><?= e($_SESSION['user_username']) ?></strong>! Berikut daftar kursus yang sudah kamu daftarkan.</p>
  </div>

  <section class="riwayat-section">

    <?php if (empty($riwayat)): ?>
    <!-- Empty state -->
    <div class="riwayat-empty">
      <div class="empty-icon">📋</div>
      <h3>Belum Ada Pendaftaran</h3>
      <p>Kamu belum mendaftarkan diri ke kursus mana pun.</p>
      <a href="kursus.php" class="btn-cta">Lihat Kursus Tersedia</a>
    </div>

    <?php else: ?>
    <!-- Summary -->
    <div class="riwayat-summary">
      <div class="summary-card">
        <span class="summary-num"><?= $total ?></span>
        <span class="summary-label">Total Pendaftaran</span>
      </div>
    </div>

    <!-- Grid kursus -->
    <div class="riwayat-grid">
      <?php foreach ($riwayat as $r): ?>
      <div class="riwayat-card">
        <div class="riwayat-card-thumb course-thumb <?= e($r['thumb_class']) ?>">
          <span class="riwayat-emoji"><?= e($r['emoji']) ?></span>
        </div>
        <div class="riwayat-card-body">
          <div class="riwayat-card-meta">
            <span class="badge-level badge-level--<?= strtolower(explode(' ', $r['level'])[0]) ?>">
              <?= e($r['level']) ?>
            </span>
            <span class="riwayat-date">
              <?= date('d M Y', strtotime($r['created_at'])) ?>
            </span>
          </div>
          <h3 class="riwayat-card-title"><?= e($r['nama_kursus']) ?></h3>
          <div class="riwayat-card-price"><?= formatRupiah((int)$r['harga']) ?></div>
          <?php if ($r['tujuan']): ?>
          <p class="riwayat-card-tujuan">
            <span class="label-tujuan">Tujuan:</span> <?= e($r['tujuan']) ?>
          </p>
          <?php endif; ?>
          <a href="detail.php?slug=<?= e($r['slug'] ?? '') ?>" class="btn-riwayat-detail">
            Lihat Detail Kursus →
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($pages > 1): ?>
    <div class="riwayat-pagination">
      <?php for ($i = 1; $i <= $pages; $i++): ?>
      <a href="riwayat.php?page=<?= $i ?>"
         class="page-dot <?= $i === $page ? 'page-dot--active' : '' ?>">
        <?= $i ?>
      </a>
      <?php endfor; ?>
    </div>
    <?php endif; ?>

    <?php endif; ?>

  </section>

  <!-- Footer kecil -->
  <div class="riwayat-footer-cta">
    <a href="kursus.php" class="btn-outline">← Lihat Kursus Lain</a>
    <a href="logout.php" class="btn-logout">Logout</a>
  </div>

  <script src="js/main.js"></script>
</body>
</html>
