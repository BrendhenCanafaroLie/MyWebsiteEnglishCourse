<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Data Pendaftaran — Admin SpeakUp</title>
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
        <h1 class="admin-page-title">Data Pendaftaran</h1>
        <p class="admin-page-sub"><?= $total ?> pendaftaran masuk</p>
      </div>
    </div>

    <?php require APP_PATH . '/views/layouts/flash.php'; ?>

    <div class="table-search-bar">
      <span>🔍</span>
      <input type="text" id="regSearch" placeholder="Cari nama, email, atau program..." oninput="filterTable(this.value, 'regTable')"/>
    </div>

    <div class="table-wrap">
      <table class="admin-table" id="regTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Email</th>
            <th>WhatsApp</th>
            <th>Program</th>
            <th>Tujuan</th>
            <th>Tanggal</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($registrations as $reg): ?>
          <tr>
            <td><?= $reg['id'] ?></td>
            <td>
              <div style="font-weight:500"><?= e($reg['nama']) ?></div>
            </td>
            <td class="small"><?= e($reg['email']) ?></td>
            <td class="small"><?= e($reg['whatsapp']) ?></td>
            <td><span class="badge-course"><?= e(mb_strimwidth($reg['nama_kursus'], 0, 30, '…')) ?></span></td>
            <td class="text-muted small cell-tujuan">
              <?php $tujuan = trim($reg['tujuan'] ?? ''); ?>
              <?php if ($tujuan): ?>
                <span class="text-preview" id="tj-prev-<?= $reg['id'] ?>"><?= e($tujuan) ?></span>
                <?php if (mb_strlen($tujuan) > 60): ?>
                <button class="btn-expand" onclick="toggleExpand(<?= $reg['id'] ?>)">▼ Selengkapnya</button>
                <span class="text-full" id="tj-full-<?= $reg['id'] ?>"><?= e($tujuan) ?></span>
                <?php endif; ?>
              <?php else: ?>
                <span style="color:var(--gray)">—</span>
              <?php endif; ?>
            </td>
            <td class="small"><?= date('d M Y', strtotime($reg['created_at'])) ?></td>
            <td>
              <button class="btn-action btn-action--delete"
                      onclick="confirmDeleteReg(<?= $reg['id'] ?>, '<?= e(addslashes($reg['nama'])) ?>')">
                Hapus
              </button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?php if (empty($registrations)): ?>
    <div class="empty-state">
      <div class="empty-icon">📋</div>
      <p>Belum ada data pendaftaran.</p>
    </div>
    <?php endif; ?>

    <!-- Pagination -->
    <?php if ($pages > 1): ?>
    <div class="pagination">
      <?php for ($i = 1; $i <= $pages; $i++): ?>
      <a href="registrations.php?page=<?= $i ?>"
         class="page-btn <?= $i === $page ? 'page-btn--active' : '' ?>">
        <?= $i ?>
      </a>
      <?php endfor; ?>
      <span class="page-info">Halaman <?= $page ?> dari <?= $pages ?></span>
    </div>
    <?php endif; ?>

  </main>

  <!-- Delete Modal -->
  <div class="modal-overlay" id="deleteRegModal" style="display:none">
    <div class="modal">
      <div class="modal-icon">🗑️</div>
      <h3 class="modal-title">Hapus Pendaftaran?</h3>
      <p class="modal-body" id="deleteRegModalBody"></p>
      <form method="POST" action="registrations.php">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" id="deleteRegId">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <div class="modal-actions">
          <button type="button" class="btn-modal-cancel" onclick="closeModal('deleteRegModal')">Batal</button>
          <button type="submit" class="btn-modal-delete">Ya, Hapus</button>
        </div>
      </form>
    </div>
  </div>

  <script src="../js/admin.js"></script>
  <script>
    function toggleExpand(id) {
      const prev = document.getElementById('tj-prev-' + id);
      const full = document.getElementById('tj-full-' + id);
      const btn  = prev.parentElement.querySelector('.btn-expand');
      if (full.style.display === 'none' || !full.style.display) {
        prev.style.display  = 'none';
        full.style.display  = 'block';
        btn.textContent = '▲ Sembunyikan';
      } else {
        prev.style.display  = 'block';
        full.style.display  = 'none';
        btn.textContent = '▼ Selengkapnya';
      }
    }
  </script>
</html>
