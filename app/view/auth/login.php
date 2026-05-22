<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login — SpeakUp English</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/global.css">
  <link rel="stylesheet" href="css/auth.css">
</head>
<body class="auth-body">

  <div class="auth-wrapper">
    <div class="auth-card">

      <div class="auth-header">
        <a href="index.php" class="logo">Speak<span>Up</span></a>
        <h1>Selamat Datang</h1>
        <p>Masuk ke akun SpeakUp English kamu</p>
      </div>

      <?php require APP_PATH . '/views/layouts/flash.php'; ?>

      <form method="POST" action="login.php" novalidate>
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <input type="hidden" name="action" value="login">

        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username"
                 placeholder="Masukkan username"
                 autocomplete="username" required autofocus/>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-password-wrap">
            <input type="password" id="password" name="password"
                   placeholder="Masukkan password"
                   autocomplete="current-password" required/>
            <button type="button" class="toggle-pw" onclick="togglePassword('password', this)">👁</button>
          </div>
        </div>

        <button type="submit" class="btn-auth">Masuk</button>
      </form>

      <div class="auth-footer">
        Belum punya akun? <a href="register.php">Daftar di sini</a>
      </div>

      <div class="auth-back">
        <a href="index.php">← Kembali ke Beranda</a>
      </div>

    </div>
  </div>

  <script src="js/auth.js"></script>
</body>
</html>
