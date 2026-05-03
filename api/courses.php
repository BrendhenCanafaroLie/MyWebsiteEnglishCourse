<?php
// ============================================================
// api/courses.php — REST API: Daftar & Detail Kursus
// SpeakUp English — Checkpoint 2
//
// GET /api/courses.php               → semua kursus
// GET /api/courses.php?slug=...      → detail kursus by slug
// GET /api/courses.php?level=...     → filter by level
// GET /api/courses.php?q=...         → pencarian real-time
// POST /api/courses.php (daftar)     → simpan pendaftaran
// ============================================================

require_once __DIR__ . '/../php/config.php';

$method = $_SERVER['REQUEST_METHOD'];

// ---- GET ----
if ($method === 'GET') {

    $db   = getDB();
    $slug  = trim($_GET['slug']  ?? '');
    $level = trim($_GET['level'] ?? '');
    $q     = trim($_GET['q']     ?? '');

    // Detail by slug
    if ($slug !== '') {
        $stmt = $db->prepare("SELECT * FROM courses WHERE slug = ? LIMIT 1");
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        if (!$row) {
            jsonResponse(['status' => 'error', 'message' => 'Kursus tidak ditemukan'], 404);
        }
        $row['harga_format'] = formatRupiah($row['harga']);
        $row['level_class']  = levelClass($row['level']);
        jsonResponse(['status' => 'ok', 'data' => $row]);
    }

    // Pencarian + Filter
    $where  = [];
    $params = [];

    if ($level !== '' && $level !== 'Semua') {
        $where[]  = 'level = ?';
        $params[] = $level;
    }
    if ($q !== '') {
        $where[]  = '(nama LIKE ? OR deskripsi LIKE ?)';
        $params[] = "%$q%";
        $params[] = "%$q%";
    }

    $sql = "SELECT id, slug, emoji, nama, level, durasi, materi, rating, siswa, harga, deskripsi, thumb_class FROM courses";
    if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
    $sql .= ' ORDER BY id ASC';

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    foreach ($rows as &$r) {
        $r['harga_format'] = formatRupiah($r['harga']);
        $r['level_class']  = levelClass($r['level']);
    }

    jsonResponse(['status' => 'ok', 'count' => count($rows), 'data' => $rows]);
}

// ---- POST (Pendaftaran) ----
if ($method === 'POST') {
    $body = json_decode(file_get_contents('php://input'), true) ?? $_POST;

    $nama      = trim($body['nama']      ?? '');
    $email     = trim($body['email']     ?? '');
    $whatsapp  = trim($body['whatsapp']  ?? '');
    $kursus_id = intval($body['kursus_id'] ?? 0);
    $tujuan    = trim($body['tujuan']    ?? '');

    // Validasi
    $errors = [];
    if (!$nama)                          $errors[] = 'Nama wajib diisi';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email tidak valid';
    if (!preg_match('/^(\+62|62|0)[0-9]{8,12}$/', preg_replace('/[\s\-]/', '', $whatsapp)))
                                         $errors[] = 'Nomor WhatsApp tidak valid';
    if ($kursus_id <= 0)                 $errors[] = 'Kursus wajib dipilih';

    if ($errors) jsonResponse(['status' => 'error', 'errors' => $errors], 422);

    $db   = getDB();
    $stmt = $db->prepare(
        "INSERT INTO pendaftaran (nama, email, whatsapp, kursus_id, tujuan) VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->execute([$nama, $email, $whatsapp, $kursus_id, $tujuan]);

    jsonResponse(['status' => 'ok', 'message' => 'Pendaftaran berhasil disimpan!', 'id' => $db->lastInsertId()]);
}

// Metode lain tidak diizinkan
http_response_code(405);
echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
