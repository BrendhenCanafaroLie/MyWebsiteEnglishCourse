<?php
// ============================================================
// register.php — Entry point untuk Register User
// ============================================================
session_start();
require_once __DIR__ . '/php/config.php';
require_once APP_PATH . '/controllers/AuthController.php';

$controller = new AuthController();
$action     = $_POST['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'register') {
    $controller->handleRegister();
} else {
    $controller->showRegister();
}
