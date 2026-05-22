<?php
// logout.php
session_start();
require_once __DIR__ . '/php/config.php';
require_once APP_PATH . '/controllers/AuthController.php';

(new AuthController())->logout();
