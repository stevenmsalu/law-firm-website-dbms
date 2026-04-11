<?php
// config/app.php
declare(strict_types=1);

if (!defined('APP_BASE_PATH')) {
    define('APP_BASE_PATH', '/law-firm-website-dbms');
}

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>