<?php

declare(strict_types=1);

if (!defined('APP_BASE_PATH')) {
    $documentRoot = isset($_SERVER['DOCUMENT_ROOT']) ? realpath((string) $_SERVER['DOCUMENT_ROOT']) : false;
    $projectRoot = realpath(__DIR__ . '/..');
    $basePath = '';

    if ($documentRoot !== false && $projectRoot !== false) {
        $normalizedDocumentRoot = str_replace('\\', '/', $documentRoot);
        $normalizedProjectRoot = str_replace('\\', '/', $projectRoot);

        if (strpos($normalizedProjectRoot, $normalizedDocumentRoot) === 0) {
            $basePath = substr($normalizedProjectRoot, strlen($normalizedDocumentRoot));
        }
    }

    $basePath = rtrim($basePath, '/');
    define('APP_BASE_PATH', $basePath === '' ? '' : $basePath);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
