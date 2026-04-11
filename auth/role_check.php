<?php

declare(strict_types=1);

require_once __DIR__ . '/auth_check.php';

function require_role(string $requiredRole): void
{
    $currentRole = $_SESSION['role'] ?? '';

    if ($currentRole !== $requiredRole) {
        header('Location: ' . APP_BASE_PATH . '/auth/login.php');
        exit;
    }
}

function require_any_role(array $allowedRoles): void
{
    $currentRole = $_SESSION['role'] ?? '';

    if (!in_array($currentRole, $allowedRoles, true)) {
        header('Location: ' . APP_BASE_PATH . '/auth/login.php');
        exit;
    }
}
