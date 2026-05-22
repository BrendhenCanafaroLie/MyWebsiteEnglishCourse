<?php
// admin/dashboard.php
session_start();
require_once __DIR__ . '/../php/config.php';
require_once APP_PATH . '/controllers/AdminController.php';

(new AdminController())->dashboard();
