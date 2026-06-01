<?php

declare(strict_types=1);

/**
 * Database bootstrap for XAMPP.
 *
 * - setup.php creates the database structure and runs the seed.
 * - seed.php inserts demo users only (admin and lawyers).
 *
 * Open in your browser:
 * http://localhost/law-firm-website-dbms/database/setup.php
 */
require __DIR__ . '/config.php';

define('SEED_FROM_SETUP', true);
require __DIR__ . '/seed.php';

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

    // Remove SQL comments, then run each statement to create tables.
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

    // Insert demo admin and lawyer accounts (skips emails that already exist).
    $seedResults = runSeed($pdo);

    require_once __DIR__ . '/../config/app.php';

    $homeUrl = APP_BASE_PATH . '/public/index.php';
    $loginUrl = APP_BASE_PATH . '/auth/login.php';

    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8" />';
    echo '<title>Database Setup</title>';
    echo '<style>body{font-family:Segoe UI,sans-serif;max-width:40rem;margin:2rem auto;padding:0 1rem;line-height:1.6;}';
    echo 'code{background:#f1f5f9;padding:0.1rem 0.35rem;border-radius:4px;}';
    echo 'ul{padding-left:1.25rem;}</style></head><body>';
    echo '<h1>Setup completed successfully</h1>';
    echo '<p>The database, tables, and demonstration users are ready.</p>';
    renderSeedResults($seedResults);
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
