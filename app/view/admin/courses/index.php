<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Kelola Kursus — Admin SpeakUp</title>
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
        <h1 class="admin-page-title">Kelola Kursus</h1>
        <p class="admin-page-sub"><?= $total ?> kursus terdaftar</p>
      </div>
      <a href="courses.php?action=create" class="btn-admin-primary">+ Tambah Kursus</a>
    </div>

    <?php require APP_PATH . '/views/layouts/flash.php'; ?>

    <!-- Search bar -->
    <div class="table-search-bar">
      <span>🔍</span>
      <input type="text" id="courseSearch" placeholder="Cari nama kursus..." oninput="filterTable(this.value)"/>
    </div>

    <div class="table-wrap">
      <table class="admin-table" id="courseTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Kursus</th>
            <th>Level</th>
            <th>Harga</th>
            <th>Siswa</th>
            <th>Rating</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($courses as $c): ?>
          <tr>
            <td><?= $c['id'] ?></td>
            <td>
              <div class="table-course-name">
                <span class="table-emoji"><?= e($c['emoji']) ?></span>
                <div>
                  <div class="fw-medium"><?= e($c['nama']) ?></div>
                  <div class="text-muted small"><?= e($c['durasi']) ?> · <?= $c['materi'] ?> materi</div>
                </div>
              </div>
            </td>
            <td><span class="badge-level <?= levelClass($c['level']) ?>"><?= e($c['level']) ?></span></td>
            <td><?= formatRupiah($c['harga']) ?></td>
            <td><?= number_format($c['siswa']) ?></td>
            <td>⭐ <?= $c['rating'] ?></td>
            <td>
              <div class="table-actions">
                <a href="courses.php?action=edit&id=<?= $c['id'] ?>" class="btn-action btn-action--edit">Edit</a>
                <button class="btn-action btn-action--delete"
                        onclick="confirmDelete(<?= $c['id'] ?>, '<?= e(addslashes($c['nama'])) ?>')">
                  Hapus
                </button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?php if (empty($courses)): ?>
    <div class="empty-state">
      <div class="empty-icon">📚</div>
      <p>Belum ada kursus. <a href="courses.php?action=create">Tambah sekarang</a>.</p>
    </div>
    <?php endif; ?>

    <!-- Pagination -->
    <?php if ($pages > 1): ?>
    <div class="pagination">
      <?php for ($i = 1; $i <= $pages; $i++): ?>
      <a href="courses.php?page=<?= $i ?>"
         class="page-btn <?= $i === $page ? 'page-btn--active' : '' ?>">
        <?= $i ?>
      </a>
      <?php endfor; ?>
      <span class="page-info">Halaman <?= $page ?> dari <?= $pages ?></span>
    </div>
    <?php endif; ?>

  </main>

  <!-- Delete Confirmation Modal -->
  <div class="modal-overlay" id="deleteModal" style="display:none">
    <div class="modal">
      <div class="modal-icon">🗑️</div>
      <h3 class="modal-title">Hapus Kursus?</h3>
      <p class="modal-body" id="deleteModalBody">Apakah kamu yakin ingin menghapus kursus ini?</p>
      <form method="POST" action="courses.php" id="deleteForm">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" id="deleteId">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <div class="modal-actions">
          <button type="button" class="btn-modal-cancel" onclick="closeModal()">Batal</button>
          <button type="submit" class="btn-modal-delete">Ya, Hapus</button>
        </div>
      </form>
    </div>
  </div>

  <script src="../js/admin.js"></script>
</body>
</html>
