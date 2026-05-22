<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Daftar User — Admin SpeakUp</title>
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
        <h1 class="admin-page-title">Daftar Akun User</h1>
        <p class="admin-page-sub"><?= $total ?> akun terdaftar</p>
      </div>
    </div>

    <?php require APP_PATH . '/views/layouts/flash.php'; ?>

    <div class="table-search-bar">
      <span>🔍</span>
      <input type="text" id="userSearch" placeholder="Cari username atau email..." oninput="filterTable(this.value, 'userTable')"/>
    </div>

    <div class="table-wrap">
      <table class="admin-table" id="userTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Bergabung</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $u): ?>
          <tr>
            <td><?= $u['id'] ?></td>
            <td>
              <div class="user-cell">
                <div class="user-avatar-sm"><?= strtoupper(substr(e($u['username']), 0, 2)) ?></div>
                <?= e($u['username']) ?>
              </div>
            </td>
            <td><?= e($u['email']) ?></td>
            <td>
              <span class="badge-role badge-role--<?= $u['role'] ?>">
                <?= $u['role'] === 'admin' ? '👑 Admin' : '👤 User' ?>
              </span>
            </td>
            <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
            <td>
              <?php if ($u['role'] !== 'admin'): ?>
              <button class="btn-action btn-action--delete"
                      onclick="confirmDeleteUser(<?= $u['id'] ?>, '<?= e(addslashes($u['username'])) ?>')">
                Hapus
              </button>
              <?php else: ?>
              <span class="text-muted small">—</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?php if (empty($users)): ?>
    <div class="empty-state">
      <div class="empty-icon">👥</div>
      <p>Belum ada user terdaftar.</p>
    </div>
    <?php endif; ?>

    <!-- Pagination -->
    <?php if ($pages > 1): ?>
    <div class="pagination">
      <?php for ($i = 1; $i <= $pages; $i++): ?>
      <a href="users.php?page=<?= $i ?>"
         class="page-btn <?= $i === $page ? 'page-btn--active' : '' ?>">
        <?= $i ?>
      </a>
      <?php endfor; ?>
      <span class="page-info">Halaman <?= $page ?> dari <?= $pages ?></span>
    </div>
    <?php endif; ?>

  </main>

  <!-- Delete Modal -->
  <div class="modal-overlay" id="deleteUserModal" style="display:none">
    <div class="modal">
      <div class="modal-icon">🗑️</div>
      <h3 class="modal-title">Hapus User?</h3>
      <p class="modal-body" id="deleteUserModalBody"></p>
      <form method="POST" action="users.php">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" id="deleteUserId">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <div class="modal-actions">
          <button type="button" class="btn-modal-cancel" onclick="closeModal('deleteUserModal')">Batal</button>
          <button type="submit" class="btn-modal-delete">Ya, Hapus</button>
        </div>
      </form>
    </div>
  </div>

  <script src="../js/admin.js"></script>
</body>
</html>
