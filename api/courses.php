<?php
// ============================================================
// api/courses.php — REST API Kursus & Pendaftaran (Checkpoint 3)
// ============================================================
session_start();
require_once __DIR__ . '/../php/config.php';
require_once APP_PATH . '/models/CourseModel.php';
require_once APP_PATH . '/models/RegistrationModel.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $courseModel = new CourseModel();
    $slug = trim($_GET['slug'] ?? '');
    if ($slug !== '') {
        $course = $courseModel->getBySlug($slug);
        if (!$course) jsonResponse(['status' => 'error', 'message' => 'Kursus tidak ditemukan.'], 404);
        jsonResponse(['status' => 'ok', 'data' => $course]);
    }
    $courses = $courseModel->search(trim($_GET['q'] ?? ''), trim($_GET['level'] ?? ''));
    jsonResponse(['status' => 'ok', 'data' => $courses]);
}

if ($method === 'POST') {
    $body      = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    $nama      = trim($body['nama']      ?? '');
    $email     = trim($body['email']     ?? '');
    $whatsapp  = trim($body['whatsapp']  ?? '');
    $kursus_id = (int) ($body['kursus_id'] ?? 0);
    $tujuan    = trim($body['tujuan']    ?? '');

    if (!$nama || !$email || !$whatsapp || !$kursus_id)
        jsonResponse(['status' => 'error', 'message' => 'Semua field wajib diisi.'], 422);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        jsonResponse(['status' => 'error', 'message' => 'Format email tidak valid.'], 422);

    $courseModel = new CourseModel();
    if (!$courseModel->getById($kursus_id))
        jsonResponse(['status' => 'error', 'message' => 'Kursus tidak ditemukan.'], 404);

    $id = (new RegistrationModel())->create([
        'user_id'   => isLoggedIn() ? ($_SESSION['user_id'] ?? null) : null,
        'nama'      => $nama,
        'email'     => $email,
        'whatsapp'  => $whatsapp,
        'kursus_id' => $kursus_id,
        'tujuan'    => $tujuan,
    ]);
    jsonResponse(['status' => 'ok', 'message' => 'Pendaftaran berhasil!', 'id' => $id], 201);
}

jsonResponse(['status' => 'error', 'message' => 'Method tidak didukung.'], 405);
