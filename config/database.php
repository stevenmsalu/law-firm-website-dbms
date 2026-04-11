<?php

declare(strict_types=1);

$dbHost = 'localhost';
$dbName = 'law_firm_db';
$dbUser = 'root';
$dbPass = '';
$dbCharset = 'utf8mb4';

$dbDsn = "mysql:host={$dbHost};dbname={$dbName};charset={$dbCharset}";

$dbOptions = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

$pdo = new PDO($dbDsn, $dbUser, $dbPass, $dbOptions);

return $pdo;
