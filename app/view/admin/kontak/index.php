<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pesan Kontak — Admin SpeakUp</title>
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
        <h1 class="admin-page-title">Pesan Kontak</h1>
        <p class="admin-page-sub">
          <?= $total ?> pesan masuk
          <?php if ($unread > 0): ?>
          · <span style="color:#f4a261;font-weight:600"><?= $unread ?> belum dibaca</span>
          <?php endif; ?>
        </p>
      </div>
    </div>

    <?php require APP_PATH . '/views/layouts/flash.php'; ?>

    <div class="table-search-bar">
      <span>🔍</span>
      <input type="text" id="kontakSearch" placeholder="Cari nama, email, atau topik..."
             oninput="filterTable(this.value, 'kontakTable')"/>
    </div>

    <?php if ($pesans): ?>
    <div class="table-wrap">
      <table class="admin-table kontak-table" id="kontakTable">
        <thead>
          <tr>
            <th>Pengirim</th>
            <th>Pesan</th>
            <th>Waktu & Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($pesans as $p): ?>
          <tr class="<?= !$p['is_read'] ? 'row-unread' : '' ?>">

            <!-- Pengirim + Topik -->
            <td class="kontak-sender-cell">
              <div class="kontak-avatar">
                <?= strtoupper(substr(e($p['nama']), 0, 2)) ?>
              </div>
              <div class="kontak-sender-info">
                <div class="fw-medium"><?= e($p['nama']) ?></div>
                <div class="text-muted small"><?= e($p['email']) ?></div>
                <?php if ($p['whatsapp']): ?>
                <div class="text-muted small">📱 <?= e($p['whatsapp']) ?></div>
                <?php endif; ?>
                <?php if ($p['topik']): ?>
                <span class="kontak-topik-badge"><?= e($p['topik']) ?></span>
                <?php endif; ?>
              </div>
            </td>

            <!-- Pesan dengan expand -->
            <td class="kontak-pesan-cell">
              <?php $pesanText = trim($p['pesan']); ?>
              <span class="kontak-pesan-preview" id="kp-prev-<?= $p['id'] ?>">
                <?= e(mb_strimwidth($pesanText, 0, 100, '…')) ?>
              </span>
              <?php if (mb_strlen($pesanText) > 100): ?>
              <button class="btn-expand" onclick="toggleKontakPesan(<?= $p['id'] ?>)">▼ Selengkapnya</button>
              <div class="kontak-pesan-full" id="kp-full-<?= $p['id'] ?>" style="display:none">
                <?= nl2br(e($pesanText)) ?>
              </div>
              <?php endif; ?>
            </td>

            <!-- Waktu + Status dalam satu cell -->
            <td class="kontak-time-cell">
              <div class="small" style="margin-bottom:6px">
                <?= date('d M Y', strtotime($p['created_at'])) ?>
                <br><span class="text-muted"><?= date('H:i', strtotime($p['created_at'])) ?></span>
              </div>
              <?php if ($p['is_read']): ?>
              <span class="k-badge k-badge--read">Dibaca</span>
              <?php else: ?>
              <span class="k-badge k-badge--unread">Baru</span>
              <?php endif; ?>
            </td>

            <!-- Aksi -->
            <td>
              <div class="table-actions" style="flex-direction:column;align-items:flex-start;gap:6px">
                <?php if (!$p['is_read']): ?>
                <form method="POST" action="kontak.php">
                  <input type="hidden" name="action" value="read">
                  <input type="hidden" name="id" value="<?= $p['id'] ?>">
                  <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                  <button type="submit" class="btn-action btn-action--edit btn-sm">
                    ✓ Tandai Dibaca
                  </button>
                </form>
                <?php endif; ?>
                <button class="btn-action btn-action--delete btn-sm"
                        onclick="confirmDeleteKontak(<?= $p['id'] ?>, '<?= e(addslashes($p['nama'])) ?>')">
                  Hapus
                </button>
              </div>
            </td>

          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?php else: ?>
    <div class="empty-state">
      <div class="empty-icon">✉️</div>
      <p>Belum ada pesan kontak masuk.</p>
    </div>
    <?php endif; ?>

    <?php if ($pages > 1): ?>
    <div class="pagination">
      <?php for ($i = 1; $i <= $pages; $i++): ?>
      <a href="kontak.php?page=<?= $i ?>"
         class="page-btn <?= $i === $page ? 'page-btn--active' : '' ?>"><?= $i ?></a>
      <?php endfor; ?>
    </div>
    <?php endif; ?>

  </main>

  <!-- Delete Modal -->
  <div class="modal-overlay" id="deleteKontakModal" style="display:none">
    <div class="modal">
      <div class="modal-icon">🗑️</div>
      <h3 class="modal-title">Hapus Pesan?</h3>
      <p class="modal-body" id="deleteKontakModalBody"></p>
      <form method="POST" action="kontak.php">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" id="deleteKontakId">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <div class="modal-actions">
          <button type="button" class="btn-modal-cancel" onclick="closeModal('deleteKontakModal')">Batal</button>
          <button type="submit" class="btn-modal-delete">Ya, Hapus</button>
        </div>
      </form>
    </div>
  </div>

  <style>
    /* Kontak table khusus: 4 kolom, lebih compact */
    .kontak-table { min-width: 540px; }

    .row-unread { background: rgba(244,162,97,0.04); }

    /* Kolom Pengirim */
    .kontak-sender-cell {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      padding: 14px 16px;
      min-width: 160px;
    }
    .kontak-avatar {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      background: linear-gradient(135deg,#f4a261,#e76f51);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.75rem;
      font-weight: 700;
      color: #fff;
      flex-shrink: 0;
    }
    .kontak-sender-info { font-size: 0.83rem; line-height: 1.5; }
    .kontak-topik-badge {
      display: inline-block;
      margin-top: 4px;
      font-size: 0.7rem;
      font-weight: 600;
      padding: 2px 8px;
      border-radius: 50px;
      background: rgba(0,180,216,0.12);
      color: var(--teal);
    }

    /* Kolom Pesan */
    .kontak-pesan-cell {
      max-width: 240px;
      font-size: 0.84rem;
      color: var(--gray);
      word-break: break-word;
      overflow-wrap: break-word;
    }
    .kontak-pesan-preview { line-height: 1.6; }
    .kontak-pesan-full {
      margin-top: 6px;
      line-height: 1.6;
      white-space: pre-wrap;
      color: var(--white);
    }

    /* Kolom Waktu + Status */
    .kontak-time-cell { min-width: 110px; vertical-align: top; }

    /* Badge status bersih */
    .k-badge {
      display: inline-block;
      font-size: 0.72rem;
      font-weight: 700;
      padding: 3px 9px;
      border-radius: 50px;
      letter-spacing: 0.3px;
    }
    .k-badge--read   { background: rgba(0,200,130,0.12); color: #00c882; }
    .k-badge--unread { background: rgba(244,162,97,0.15); color: #f4a261; }

    /* Tombol aksi lebih kecil */
    .btn-sm { font-size: 0.78rem !important; padding: 5px 10px !important; }
  </style>

  <script src="../js/admin.js"></script>
  <script>
    function confirmDeleteKontak(id, nama) {
      document.getElementById('deleteKontakId').value = id;
      document.getElementById('deleteKontakModalBody').textContent =
        `Pesan dari "${nama}" akan dihapus permanen. Yakin?`;
      showModal('deleteKontakModal');
    }
    function toggleKontakPesan(id) {
      const prev = document.getElementById('kp-prev-' + id);
      const full = document.getElementById('kp-full-' + id);
      const btn  = prev.parentElement.querySelector('.btn-expand');
      if (full.style.display === 'none' || !full.style.display) {
        prev.style.display = 'none';
        full.style.display = 'block';
        btn.textContent = '▲ Sembunyikan';
      } else {
        prev.style.display = 'inline';
        full.style.display = 'none';
        btn.textContent = '▼ Selengkapnya';
      }
    }
  </script>
</body>
</html>
