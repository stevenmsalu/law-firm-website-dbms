<?php

declare(strict_types=1);

require_once __DIR__ . '/auth_check.php';

/**
 * Allow only one role to access a page (for example, admin-only pages).
 */
function require_role(string $requiredRole): void
{
    $currentRole = (string) ($_SESSION['role'] ?? '');

    if ($currentRole !== $requiredRole) {
        header('Location: ' . APP_BASE_PATH . '/auth/login.php');
        exit;
    }
}

/**
 * Allow any role in the list to access a page (for example, client or lawyer).
 */
function require_any_role(array $allowedRoles): void
{
    $currentRole = (string) ($_SESSION['role'] ?? '');

    if (!in_array($currentRole, $allowedRoles, true)) {
        header('Location: ' . APP_BASE_PATH . '/auth/login.php');
        exit;
    }
}
