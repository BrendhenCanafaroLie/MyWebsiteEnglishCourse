<?php
// ============================================================
// login.php — Entry point untuk auth Login
// ============================================================
session_start();
require_once __DIR__ . '/php/config.php';
require_once APP_PATH . '/controllers/AuthController.php';

$controller = new AuthController();
$action     = $_POST['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'login') {
    $controller->handleLogin();
} else {
    $controller->showLogin();
}
