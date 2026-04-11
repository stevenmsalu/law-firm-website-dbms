<?php
// auth/role_check.php
declare(strict_types=1);

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (empty($_SESSION['user_id'])) {
    header('Location: /law-firm-website-dbms/auth/login.php');
    exit;
}

function require_role(string $requiredRole): void
{
    $currentRole = $_SESSION['role'] ?? '';

    if ($currentRole !== $requiredRole) {
        header('Location: /law-firm-website-dbms/auth/login.php');
        exit;
    }
}

function require_any_role(array $allowedRoles): void
{
    $currentRole = $_SESSION['role'] ?? '';

    if (!in_array($currentRole, $allowedRoles, true)) {
        header('Location: /law-firm-website-dbms/auth/login.php');
        exit;
    }
}
?>