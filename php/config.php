<?php
// ============================================================
// php/config.php — Konfigurasi Database & Helper Global
// SpeakUp English — Checkpoint 3
// ============================================================

define('DB_HOST',    'sql305.infinityfree.com');
define('DB_USER',    'if0_41818911');
define('DB_PASS',    '');        // ← isi password MySQL kamu di sini
define('DB_NAME',    'if0_41818911_speakup');
define('DB_CHARSET', 'utf8mb4');

// Path helpers
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH',  BASE_PATH . '/app');

/**
 * Koneksi PDO singleton
 */
function getDB(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', DB_HOST, DB_NAME, DB_CHARSET);
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            http_response_code(500);
            die(json_encode(['status' => 'error', 'message' => 'Koneksi database gagal.']));
        }
    }

    return $pdo;
}

/**
 * Kirim JSON response lalu exit
 */
function jsonResponse(mixed $data, int $code = 200): void
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

/**
 * Format angka ke Rupiah
 */
function formatRupiah(int $amount): string
{
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

/**
 * Level ke class CSS badge
 */
function levelClass(string $level): string
{
    return match ($level) {
        'Pemula'      => 'level-beginner',
        'Menengah'    => 'level-intermediate',
        'Lanjutan'    => 'level-advanced',
        'Sertifikasi' => 'level-cert',
        default       => 'level-beginner',
    };
}

/**
 * Redirect helper
 */
function redirect(string $url): void
{
    header("Location: $url");
    exit;
}

// ============================================================
// Login Rate Limiting (max 5 percobaan per 15 menit per IP)
// ============================================================
define('LOGIN_MAX_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_MINUTES', 15);

/**
 * Cek apakah IP sedang dikunci karena terlalu banyak percobaan login
 */
function isLoginLocked(): bool
{
    try {
        $ip   = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $db   = getDB();
        $stmt = $db->prepare(
            'SELECT attempts, last_attempt FROM login_attempts WHERE ip = ? LIMIT 1'
        );
        $stmt->execute([$ip]);
        $row = $stmt->fetch();

        if (!$row) return false;

        $elapsed = (time() - strtotime($row['last_attempt'])) / 60;
        if ($elapsed > LOGIN_LOCKOUT_MINUTES) {
            $db->prepare('DELETE FROM login_attempts WHERE ip = ?')->execute([$ip]);
            return false;
        }
        return (int) $row['attempts'] >= LOGIN_MAX_ATTEMPTS;
    } catch (PDOException $e) {
        // Tabel belum ada (misal belum import database.sql terbaru) — skip rate limit
        return false;
    }
}

/**
 * Catat percobaan login gagal
 */
function recordFailedLogin(): void
{
    try {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        getDB()->prepare(
            'INSERT INTO login_attempts (ip, attempts) VALUES (?, 1)
             ON DUPLICATE KEY UPDATE attempts = attempts + 1, last_attempt = CURRENT_TIMESTAMP'
        )->execute([$ip]);
    } catch (PDOException $e) { /* tabel belum ada, skip */ }
}

/**
 * Hapus catatan percobaan login (setelah login berhasil)
 */
function clearLoginAttempts(): void
{
    try {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        getDB()->prepare('DELETE FROM login_attempts WHERE ip = ?')->execute([$ip]);
    } catch (PDOException $e) { /* tabel belum ada, skip */ }
}

/**
 * Sisa waktu lockout dalam menit
 */
function loginLockoutMinutesLeft(): int
{
    try {
        $ip   = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $stmt = getDB()->prepare('SELECT last_attempt FROM login_attempts WHERE ip = ? LIMIT 1');
        $stmt->execute([$ip]);
        $row = $stmt->fetch();
        if (!$row) return 0;
        $elapsed = (time() - strtotime($row['last_attempt'])) / 60;
        return (int) max(0, ceil(LOGIN_LOCKOUT_MINUTES - $elapsed));
    } catch (PDOException $e) {
        return 0;
    }
}

/**
 * Escape output HTML
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Flash message: set
 */
function setFlash(string $type, string $message): void
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Flash message: get & clear
 */
function getFlash(): ?array
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

/**
 * Cek apakah user sudah login sebagai admin
 */
function isAdmin(): bool
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    return isset($_SESSION['user_id']) && ($_SESSION['user_role'] ?? '') === 'admin';
}

/**
 * Cek apakah user sudah login (admin atau user biasa)
 */
function isLoggedIn(): bool
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    return isset($_SESSION['user_id']);
}

/**
 * Guard: redirect ke login jika bukan admin
 */
function requireAdmin(): void
{
    if (!isAdmin()) {
        setFlash('error', 'Akses ditolak. Silakan login sebagai admin.');
        // Deteksi apakah dipanggil dari dalam folder admin/ atau root
        $isInAdminFolder = str_contains($_SERVER['SCRIPT_FILENAME'] ?? '', DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR);
        redirect($isInAdminFolder ? '../login.php' : 'login.php');
    }
}

/**
 * Generate CSRF token
 */
function csrfToken(): string
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validasi CSRF token
 */
function verifyCsrf(string $token): bool
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}
