<?php

declare(strict_types=1);

/**
 * Returns a PDO connection to the application database.
 * Include from PHP pages: $pdo = require __DIR__ . '/../database/connection.php';
 */
require __DIR__ . '/config.php';

$dbDsn = "mysql:host={$dbHost};dbname={$dbName};charset={$dbCharset}";

$dbOptions = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

return new PDO($dbDsn, $dbUser, $dbPass, $dbOptions);
