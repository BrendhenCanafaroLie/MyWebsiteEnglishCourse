<!-- app/views/layouts/admin_nav.php -->

<!-- Mobile Top Bar (hanya muncul di < 900px) -->
<div class="admin-mobile-topbar">
  <a href="dashboard.php" class="admin-mobile-logo">Speak<span>Up</span></a>
  <button class="admin-hamburger" id="adminHamburger" onclick="toggleAdminNav()" aria-label="Menu">
    <span></span><span></span><span></span>
  </button>
</div>

<!-- Overlay gelap saat sidebar terbuka di mobile -->
<div class="admin-overlay" id="adminOverlay" onclick="closeAdminNav()"></div>

<!-- Sidebar Nav -->
<nav class="admin-nav" id="adminNav">
  <a href="dashboard.php" class="admin-logo">Speak<span>Up</span> <span class="admin-tag">Admin</span></a>

  <ul class="admin-nav-links">
    <li>
      <a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>">
        <span class="nav-icon">📊</span> Dashboard
      </a>
    </li>
    <li>
      <a href="courses.php" class="<?= str_contains(basename($_SERVER['PHP_SELF']), 'course') ? 'active' : '' ?>">
        <span class="nav-icon">📚</span> Kelola Kursus
      </a>
    </li>
    <li>
      <a href="users.php" class="<?= basename($_SERVER['PHP_SELF']) === 'users.php' ? 'active' : '' ?>">
        <span class="nav-icon">👥</span> Daftar User
      </a>
    </li>
    <li>
      <a href="registrations.php" class="<?= basename($_SERVER['PHP_SELF']) === 'registrations.php' ? 'active' : '' ?>">
        <span class="nav-icon">📋</span> Pendaftaran
      </a>
    </li>
    <li>
      <a href="kontak.php" class="<?= basename($_SERVER['PHP_SELF']) === 'kontak.php' ? 'active' : '' ?>">
        <span class="nav-icon">✉️</span> Pesan Kontak
      </a>
    </li>
  </ul>

  <div class="admin-nav-footer">
    <div class="admin-user-info">
      <div class="admin-avatar"><?= strtoupper(substr(e($_SESSION['user_username']), 0, 2)) ?></div>
      <div>
        <div class="admin-username"><?= e($_SESSION['user_username']) ?></div>
        <div class="admin-role-label">Administrator</div>
      </div>
    </div>
    <div class="admin-nav-actions">
      <a href="../index.php" class="btn-nav-secondary" target="_blank">🌐 Lihat Website</a>
      <a href="../logout.php" class="btn-nav-logout">Logout →</a>
    </div>
  </div>
</nav>
