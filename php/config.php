<?php
// ============================================================
// php/config.php — Konfigurasi Koneksi Database
// SpeakUp English — Checkpoint 2
// ============================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // Ganti sesuai user MySQL kamu
define('DB_PASS', '');           // Ganti sesuai password MySQL kamu
define('DB_NAME', 'speakup_english');
define('DB_CHARSET', 'utf8mb4');

/**
 * Membuat koneksi PDO ke MySQL.
 * Mengembalikan objek PDO atau menghentikan script jika gagal.
 */
function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Tampilkan pesan error yang ramah (produksi: log saja)
            http_response_code(500);
            die(json_encode([
                'status'  => 'error',
                'message' => 'Koneksi database gagal: ' . $e->getMessage()
            ]));
        }
    }
    return $pdo;
}

/**
 * Helper: kirim JSON response
 */
function jsonResponse(mixed $data, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

/**
 * Helper: format harga ke Rupiah
 */
function formatRupiah(int $harga): string {
    return 'Rp ' . number_format($harga, 0, ',', '.');
}

/**
 * Helper: level ke class CSS
 */
function levelClass(string $level): string {
    return match($level) {
        'Pemula'      => 'level-beginner',
        'Menengah'    => 'level-intermediate',
        'Lanjutan'    => 'level-advanced',
        'Sertifikasi' => 'level-cert',
        default       => 'level-beginner',
    };
}
