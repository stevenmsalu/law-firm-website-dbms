<?php

declare(strict_types=1);

/**
 * Project entry point.
 * Redirects visitors from the project root to the public homepage.
 */
require_once __DIR__ . '/config/app.php';

header('Location: ' . APP_BASE_PATH . '/public/index.php');
exit;
