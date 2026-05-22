<?php
// admin/registrations.php — Data Pendaftaran
session_start();
require_once __DIR__ . '/../php/config.php';
require_once APP_PATH . '/controllers/AdminController.php';

$controller = new AdminController();
$action     = $_REQUEST['action'] ?? 'index';
$id         = (int) ($_REQUEST['id'] ?? 0);

match (true) {
    $action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST' => $controller->registrationDelete($id),
    default => $controller->registrationIndex(),
};
