<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/app.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (empty($_SESSION['user_id'])) {
    header('Location: ' . APP_BASE_PATH . '/auth/login.php');
    exit;
}
