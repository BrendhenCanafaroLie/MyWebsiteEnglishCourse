<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Daftar Akun — SpeakUp English</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/global.css">
  <link rel="stylesheet" href="css/auth.css">
</head>
<body class="auth-body">

  <div class="auth-wrapper">
    <div class="auth-card auth-card--wide">

      <div class="auth-header">
        <a href="index.php" class="logo">Speak<span>Up</span></a>
        <h1>Buat Akun</h1>
        <p>Bergabung dengan komunitas SpeakUp English</p>
      </div>

      <?php require APP_PATH . '/views/layouts/flash.php'; ?>

      <form method="POST" action="register.php" id="registerForm" novalidate>
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <input type="hidden" name="action" value="register">

        <div class="form-group">
          <label for="username">Username *</label>
          <input type="text" id="username" name="username"
                 placeholder="contoh: budi_santoso"
                 autocomplete="username" required/>
          <div class="field-hint">3–30 karakter, huruf/angka/underscore saja.</div>
          <div class="field-error" id="err-username"></div>
        </div>

        <div class="form-group">
          <label for="email">Email *</label>
          <input type="email" id="email" name="email"
                 placeholder="nama@email.com"
                 autocomplete="email" required/>
          <div class="field-error" id="err-email"></div>
        </div>

        <div class="form-group">
          <label for="password">Password *</label>
          <div class="input-password-wrap">
            <input type="password" id="password" name="password"
                   placeholder="Minimal 6 karakter"
                   autocomplete="new-password" required/>
            <button type="button" class="toggle-pw" onclick="togglePassword('password', this)">👁</button>
          </div>
          <div class="field-error" id="err-password"></div>
        </div>

        <div class="form-group">
          <label for="password2">Konfirmasi Password *</label>
          <div class="input-password-wrap">
            <input type="password" id="password2" name="password2"
                   placeholder="Ulangi password"
                   autocomplete="new-password" required/>
            <button type="button" class="toggle-pw" onclick="togglePassword('password2', this)">👁</button>
          </div>
          <div class="field-error" id="err-password2"></div>
        </div>

        <button type="submit" class="btn-auth">Daftar Sekarang</button>
      </form>

      <div class="auth-footer">
        Sudah punya akun? <a href="login.php">Login di sini</a>
      </div>

      <div class="auth-back">
        <a href="index.php">← Kembali ke Beranda</a>
      </div>

    </div>
  </div>

  <script src="js/auth.js"></script>
</body>
</html>
