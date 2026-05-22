<?php
// admin/kontak.php — Pesan Kontak dari pengunjung
session_start();
require_once __DIR__ . '/../php/config.php';
require_once APP_PATH . '/controllers/AdminController.php';

$controller = new AdminController();
$action     = $_REQUEST['action'] ?? 'index';
$id         = (int) ($_REQUEST['id'] ?? 0);

match (true) {
    $action === 'read'   && $_SERVER['REQUEST_METHOD'] === 'POST' => $controller->kontakRead($id),
    $action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST' => $controller->kontakDelete($id),
    default => $controller->kontakIndex(),
};
