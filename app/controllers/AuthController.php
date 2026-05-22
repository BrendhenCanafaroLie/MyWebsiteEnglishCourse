<?php
// ============================================================
// app/controllers/AuthController.php
// Handles: Login, Register, Logout
// ============================================================

require_once APP_PATH . '/models/UserModel.php';

class AuthController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // ---- GET /login.php ----
    public function showLogin(): void
    {
        if (isLoggedIn()) {
            $this->redirectToBase(isAdmin() ? 'admin/dashboard.php' : 'index.php');
        }
        $flash = getFlash();
        require APP_PATH . '/views/auth/login.php';
    }

    // ---- POST /login.php ----
    public function handleLogin(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $csrf     = $_POST['csrf_token'] ?? '';

        if (!verifyCsrf($csrf)) {
            setFlash('error', 'Token tidak valid. Coba lagi.');
            $this->redirectToBase('login.php');
        }

        // Cek rate limit
        if (isLoginLocked()) {
            $menit = loginLockoutMinutesLeft();
            setFlash('error', "Terlalu banyak percobaan login. Coba lagi dalam {$menit} menit.");
            $this->redirectToBase('login.php');
        }

        $user = $this->userModel->findByUsername($username);

        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            recordFailedLogin();
            setFlash('error', 'Username atau password salah.');
            $this->redirectToBase('login.php');
        }

        // Login berhasil — reset percobaan
        clearLoginAttempts();

        // Simpan session
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_regenerate_id(true);
        $_SESSION['user_id']       = $user['id'];
        $_SESSION['user_username'] = $user['username'];
        $_SESSION['user_role']     = $user['role'];

        if ($user['role'] === 'admin') {
            $this->redirectToBase('admin/dashboard.php');
        } else {
            $this->redirectToBase('index.php');
        }
    }

    // ---- GET /register.php ----
    public function showRegister(): void
    {
        if (isLoggedIn()) $this->redirectToBase('index.php');
        $flash = getFlash();
        require APP_PATH . '/views/auth/register.php';
    }

    // ---- POST /register.php ----
    public function handleRegister(): void
    {
        $username  = trim($_POST['username'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $password  = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';
        $csrf      = $_POST['csrf_token'] ?? '';

        if (!verifyCsrf($csrf)) {
            setFlash('error', 'Token tidak valid.');
            $this->redirectToBase('register.php');
        }

        $errors = $this->validateRegister($username, $email, $password, $password2);
        if ($errors) {
            setFlash('error', implode('<br>', $errors));
            $this->redirectToBase('register.php');
        }

        $this->userModel->create($username, $email, $password);
        setFlash('success', 'Akun berhasil dibuat! Silakan login.');
        $this->redirectToBase('login.php');
    }

    // ---- GET /logout.php ----
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        $this->redirectToBase('login.php');
    }

    // ---- Private helpers ----

    /**
     * Redirect ke halaman relatif dari root project.
     * Mendeteksi subfolder otomatis dari REQUEST_URI.
     */
    private function redirectToBase(string $page): void
    {
        // Ambil base path dari script yang sedang jalan
        // Contoh: /MyWebsiteEnglishCourse/login.php → /MyWebsiteEnglishCourse/
        $scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');

        // Kalau dipanggil dari subfolder (misal /admin/), naik satu level
        if (str_ends_with($scriptDir, '/admin')) {
            $scriptDir = dirname($scriptDir);
        }

        $base = rtrim($scriptDir, '/');
        header("Location: $base/$page");
        exit;
    }

    private function validateRegister(
        string $username,
        string $email,
        string $password,
        string $password2
    ): array {
        $errors = [];

        if (strlen($username) < 3 || strlen($username) > 30)
            $errors[] = 'Username harus 3–30 karakter.';
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username))
            $errors[] = 'Username hanya boleh huruf, angka, dan underscore.';
        if ($this->userModel->usernameExists($username))
            $errors[] = 'Username sudah digunakan.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $errors[] = 'Format email tidak valid.';
        if ($this->userModel->emailExists($email))
            $errors[] = 'Email sudah terdaftar.';
        if (strlen($password) < 6)
            $errors[] = 'Password minimal 6 karakter.';
        if ($password !== $password2)
            $errors[] = 'Konfirmasi password tidak cocok.';

        return $errors;
    }
}
