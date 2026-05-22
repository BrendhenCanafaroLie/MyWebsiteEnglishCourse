<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Admin — SpeakUp English</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/global.css">
  <link rel="stylesheet" href="../css/admin.css">
</head>
<body class="admin-body">

  <?php require APP_PATH . '/views/layouts/admin_nav.php'; ?>

  <main class="admin-main">
    <div class="admin-topbar">
      <div>
        <h1 class="admin-page-title">Dashboard</h1>
        <p class="admin-page-sub">Selamat datang, <strong><?= e($_SESSION['user_username']) ?></strong>!</p>
      </div>
      <a href="courses.php?action=create" class="btn-admin-primary">+ Tambah Kursus</a>
    </div>

    <?php require APP_PATH . '/views/layouts/flash.php'; ?>

    <!-- Stats Cards -->
    <div class="stats-grid">
      <div class="stat-card stat-card--teal">
        <div class="stat-card-icon">📚</div>
        <div class="stat-card-body">
          <div class="stat-card-num"><?= $stats['total_kursus'] ?></div>
          <div class="stat-card-label">Total Kursus</div>
        </div>
      </div>
      <div class="stat-card stat-card--gold">
        <div class="stat-card-icon">👥</div>
        <div class="stat-card-body">
          <div class="stat-card-num"><?= number_format($stats['course_stats']['total_siswa'] ?? 0, 0, ',', '.') ?></div>
          <div class="stat-card-label">Total Siswa</div>
        </div>
      </div>
      <div class="stat-card stat-card--blue">
        <div class="stat-card-icon">📋</div>
        <div class="stat-card-body">
          <div class="stat-card-num"><?= $stats['total_pendaftaran'] ?></div>
          <div class="stat-card-label">Pendaftaran</div>
        </div>
      </div>
      <div class="stat-card stat-card--purple">
        <div class="stat-card-icon">👤</div>
        <div class="stat-card-body">
          <div class="stat-card-num"><?= $stats['total_users'] ?></div>
          <div class="stat-card-label">Akun User</div>
        </div>
      </div>
      <div class="stat-card stat-card--orange">
        <div class="stat-card-icon">✉️</div>
        <div class="stat-card-body">
          <div class="stat-card-num">
            <?= $stats['total_pesan'] ?>
            <?php if ($stats['pesan_unread'] > 0): ?>
            <span class="stat-badge-new"><?= $stats['pesan_unread'] ?> baru</span>
            <?php endif; ?>
          </div>
          <div class="stat-card-label">Pesan Kontak</div>
        </div>
      </div>
    </div>

    <!-- Recent Registrations -->
    <div class="admin-section">
      <div class="admin-section-header">
        <h2>Pendaftaran Terbaru</h2>
        <a href="registrations.php" class="btn-admin-link">Lihat Semua →</a>
      </div>

      <?php if ($recent_registrations): ?>
      <div class="table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Program</th>
              <th>Tanggal</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recent_registrations as $reg): ?>
            <tr>
              <td><?= $reg['id'] ?></td>
              <td><?= e($reg['nama']) ?></td>
              <td><?= e($reg['email']) ?></td>
              <td><span class="badge-course"><?= e($reg['nama_kursus']) ?></span></td>
              <td><?= date('d M Y', strtotime($reg['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php else: ?>
      <div class="empty-state">
        <div class="empty-icon">📋</div>
        <p>Belum ada pendaftaran masuk.</p>
      </div>
      <?php endif; ?>
    </div>

    <!-- Quick Links -->
    <div class="quick-links">
      <a href="courses.php" class="quick-link-card">
        <span class="ql-icon">📚</span>
        <span class="ql-label">Kelola Kursus</span>
      </a>
      <a href="users.php" class="quick-link-card">
        <span class="ql-icon">👥</span>
        <span class="ql-label">Daftar User</span>
      </a>
      <a href="registrations.php" class="quick-link-card">
        <span class="ql-icon">📋</span>
        <span class="ql-label">Pendaftaran</span>
      </a>
      <a href="kontak.php" class="quick-link-card">
        <span class="ql-icon">✉️</span>
        <span class="ql-label">Pesan Kontak <?= $stats['pesan_unread'] > 0 ? "({$stats['pesan_unread']})" : '' ?></span>
      </a>
      <a href="../index.php" target="_blank" class="quick-link-card">
        <span class="ql-icon">🌐</span>
        <span class="ql-label">Lihat Website</span>
      </a>
    </div>

  </main>

  <script src="../js/admin.js"></script>
</body>
</html>
