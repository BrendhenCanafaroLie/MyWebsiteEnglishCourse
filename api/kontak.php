<?php
// ============================================================
// api/kontak.php — Simpan pesan kontak (Checkpoint 3)
// ============================================================
session_start();
require_once __DIR__ . '/../php/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST')
    jsonResponse(['status' => 'error', 'message' => 'Method tidak didukung.'], 405);

$body     = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$nama     = trim($body['nama']     ?? '');
$email    = trim($body['email']    ?? '');
$whatsapp = trim($body['whatsapp'] ?? '');
$topik    = trim($body['topik']    ?? '');
$pesan    = trim($body['pesan']    ?? '');

if (!$nama || !$email || !$pesan)
    jsonResponse(['status' => 'error', 'message' => 'Nama, email, dan pesan wajib diisi.'], 422);
if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    jsonResponse(['status' => 'error', 'message' => 'Format email tidak valid.'], 422);

$db   = getDB();
$stmt = $db->prepare(
    'INSERT INTO pesan_kontak (nama, email, whatsapp, topik, pesan) VALUES (?, ?, ?, ?, ?)'
);
$stmt->execute([$nama, $email, $whatsapp, $topik, $pesan]);

jsonResponse(['status' => 'ok', 'message' => 'Pesan berhasil dikirim! Kami akan merespons segera.'], 201);
