<?php
// ============================================================
// api/kontak.php — API: Simpan Pesan Kontak
// POST /api/kontak.php
// ============================================================

require_once __DIR__ . '/../php/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['status' => 'error', 'message' => 'Hanya menerima POST'], 405);
}

$body    = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$nama    = trim($body['nama']     ?? '');
$email   = trim($body['email']    ?? '');
$wa      = trim($body['whatsapp'] ?? '');
$topik   = trim($body['topik']    ?? '');
$pesan   = trim($body['pesan']    ?? '');

$errors = [];
if (!$nama)                               $errors[] = 'Nama wajib diisi';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email tidak valid';
if (!$pesan)                              $errors[] = 'Pesan wajib diisi';

if ($errors) jsonResponse(['status' => 'error', 'errors' => $errors], 422);

$db   = getDB();
$stmt = $db->prepare(
    "INSERT INTO pesan_kontak (nama, email, whatsapp, topik, pesan) VALUES (?, ?, ?, ?, ?)"
);
$stmt->execute([$nama, $email, $wa, $topik, $pesan]);

jsonResponse(['status' => 'ok', 'message' => 'Pesan berhasil dikirim!']);
