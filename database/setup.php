<?php

declare(strict_types=1);

/**
 * One-time database setup for XAMPP.
 *
 * 1. Runs database/schema.sql (creates database and tables)
 * 2. Creates the default admin account
 *
 * Open in your browser:
 * http://localhost/law-firm-website-dbms/database/setup.php
 */
require __DIR__ . '/config.php';

$adminName = 'Admin User';
$adminEmail = 'admin@lawfirm.com';
$adminPassword = 'admin123';
$adminRole = 'admin';

header('Content-Type: text/html; charset=UTF-8');

try {
    $schemaFile = __DIR__ . '/schema.sql';

    if (!is_file($schemaFile)) {
        throw new RuntimeException('schema.sql not found.');
    }

    $sql = file_get_contents($schemaFile);

    if ($sql === false) {
        throw new RuntimeException('Could not read schema.sql.');
    }

    $sql = preg_replace('/^\s*--.*$/m', '', $sql);

    $pdo = new PDO(
        "mysql:host={$dbHost};charset={$dbCharset}",
        $dbUser,
        $dbPass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    foreach (explode(';', $sql) as $statement) {
        $statement = trim($statement);
        if ($statement !== '') {
            $pdo->exec($statement);
        }
    }

    $pdo = require __DIR__ . '/connection.php';

    $stmt = $pdo->prepare(
        'INSERT INTO users (name, email, password, role)
         VALUES (:name, :email, :password, :role)
         ON DUPLICATE KEY UPDATE
            name = VALUES(name),
            password = VALUES(password),
            role = VALUES(role)'
    );

    $stmt->execute([
        'name' => $adminName,
        'email' => $adminEmail,
        'password' => password_hash($adminPassword, PASSWORD_DEFAULT),
        'role' => $adminRole,
    ]);

    require_once __DIR__ . '/../config/app.php';

    $homeUrl = APP_BASE_PATH . '/public/index.php';
    $loginUrl = APP_BASE_PATH . '/auth/login.php';

    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8" />';
    echo '<title>Database Setup</title>';
    echo '<style>body{font-family:Segoe UI,sans-serif;max-width:36rem;margin:2rem auto;padding:0 1rem;line-height:1.6;}';
    echo 'code{background:#f1f5f9;padding:0.1rem 0.35rem;border-radius:4px;}</style></head><body>';
    echo '<h1>Setup completed successfully</h1>';
    echo '<p>The database, tables, and admin account are ready.</p>';
    echo '<p><strong>Admin email:</strong> ' . htmlspecialchars($adminEmail, ENT_QUOTES, 'UTF-8') . '<br />';
    echo '<strong>Admin password:</strong> ' . htmlspecialchars($adminPassword, ENT_QUOTES, 'UTF-8') . '</p>';
    echo '<p><a href="' . htmlspecialchars($homeUrl, ENT_QUOTES, 'UTF-8') . '">Go to the website home page</a><br />';
    echo '<a href="' . htmlspecialchars($loginUrl, ENT_QUOTES, 'UTF-8') . '">Go to login</a></p>';
    echo '</body></html>';
} catch (Throwable $e) {
    http_response_code(500);
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8" /><title>Setup Failed</title></head><body>';
    echo '<h1>Setup failed</h1>';
    echo '<p>Check that MySQL is running in XAMPP, then open this page again.</p>';
    echo '<pre>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</pre>';
    echo '</body></html>';
}
