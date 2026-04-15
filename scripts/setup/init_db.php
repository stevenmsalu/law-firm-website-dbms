<?php

/**  How to run script
 * php scripts/setup/init_db.php
*/

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    exit("Run from CLI only.\n");
}

$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbCharset = 'utf8mb4';

// 1. Connect WITHOUT selecting a database
$dsn = "mysql:host={$dbHost};charset={$dbCharset}";

$pdo = new PDO($dsn, $dbUser, $dbPass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

echo "Connected to MySQL...\n";

// 2. Load schema.sql
$schemaPath = __DIR__ . '/../../../database/schema.sql';

if (!file_exists($schemaPath)) {
    exit("schema.sql not found.\n");
}

$sql = file_get_contents($schemaPath);

// 3. Execute SQL (multiple statements)
$pdo->exec($sql);

echo "Database and tables created successfully.\n";