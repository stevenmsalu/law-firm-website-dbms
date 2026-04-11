<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Run this script from CLI only.');
}

$pdo = require __DIR__ . '/../../config/database.php';

$name = 'Admin User';
$email = 'admin@lawfirm.com';
$plainPassword = 'admin123';
$role = 'admin';
$passwordHash = password_hash($plainPassword, PASSWORD_DEFAULT);

$stmt = $pdo->prepare(
    'INSERT INTO users (name, email, password, role)
     VALUES (:name, :email, :password, :role)
     ON DUPLICATE KEY UPDATE
        name = VALUES(name),
        password = VALUES(password),
        role = VALUES(role)'
);

$stmt->execute([
    'name' => $name,
    'email' => $email,
    'password' => $passwordHash,
    'role' => $role,
]);

echo "Admin user created/updated successfully.\n";
echo "Email: {$email}\n";
echo "Password: {$plainPassword}\n";
