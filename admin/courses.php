<?php
// admin/courses.php — Kelola Kursus (CRUD)
session_start();
require_once __DIR__ . '/../php/config.php';
require_once APP_PATH . '/controllers/AdminController.php';

$controller = new AdminController();
$action     = $_REQUEST['action'] ?? 'index';
$id         = (int) ($_REQUEST['id'] ?? 0);

match (true) {
    $action === 'create'                                  => $controller->courseCreate(),
    $action === 'store'  && $_SERVER['REQUEST_METHOD'] === 'POST' => $controller->courseStore(),
    $action === 'edit'   && $id > 0                       => $controller->courseEdit($id),
    $action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST' => $controller->courseUpdate($id),
    $action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST' => $controller->courseDelete($id),
    default                                               => $controller->courseIndex(),
};
