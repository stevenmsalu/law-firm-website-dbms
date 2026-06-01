<?php

declare(strict_types=1);

/**
 * Demonstration user seeding for Zimba & Partners.
 *
 * - setup.php creates the database structure and runs this script.
 * - seed.php inserts demo users only (admin and lawyers from public/lawyers.php).
 *
 * Safe to run multiple times: existing emails are skipped, not duplicated.
 */

/**
 * Insert a user when the email is not already registered.
 *
 * @return 'inserted'|'skipped'
 */
function seedUser(PDO $pdo, string $name, string $email, string $plainPassword, string $role): string
{
    $check = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
    $check->execute(['email' => $email]);

    if ($check->fetch()) {
        return 'skipped';
    }

    $insert = $pdo->prepare(
        'INSERT INTO users (name, email, password, role)
         VALUES (:name, :email, :password, :role)'
    );
    $insert->execute([
        'name' => $name,
        'email' => $email,
        'password' => password_hash($plainPassword, PASSWORD_DEFAULT),
        'role' => $role,
    ]);

    return 'inserted';
}

/**
 * @return list<array{label: string, name: string, email: string, password: string, role: string, status: string}>
 */
function runSeed(PDO $pdo): array
{
    $adminPassword = 'admin123';
    $lawyerPassword = 'lawyer123';

    $seedUsers = [
        ['label' => 'Admin', 'name' => 'Admin User', 'email' => 'admin@lawfirm.com', 'password' => $adminPassword, 'role' => 'admin'],
        ['label' => 'Lawyer', 'name' => 'Mwansa Zimba', 'email' => 'mwansa.zimba@lawfirm.com', 'password' => $lawyerPassword, 'role' => 'lawyer'],
        ['label' => 'Lawyer', 'name' => 'Jessica Zimba', 'email' => 'jessica.zimba@lawfirm.com', 'password' => $lawyerPassword, 'role' => 'lawyer'],
        ['label' => 'Lawyer', 'name' => 'Bupe Zimba', 'email' => 'bupe.zimba@lawfirm.com', 'password' => $lawyerPassword, 'role' => 'lawyer'],
    ];

    $results = [];

    foreach ($seedUsers as $user) {
        $status = seedUser($pdo, $user['name'], $user['email'], $user['password'], $user['role']);
        $results[] = [
            'label' => $user['label'],
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => $user['password'],
            'role' => $user['role'],
            'status' => $status,
        ];
    }

    return $results;
}

/**
 * @param list<array{label: string, name: string, email: string, password: string, role: string, status: string}> $results
 */
function renderSeedResults(array $results): void
{
    echo '<h2>Seed summary</h2><ul>';

    foreach ($results as $result) {
        $action = $result['status'] === 'inserted' ? 'inserted' : 'skipped';
        echo '<li><strong>' . htmlspecialchars($result['label'], ENT_QUOTES, 'UTF-8') . ':</strong> ';
        echo htmlspecialchars($result['name'], ENT_QUOTES, 'UTF-8') . ' — ';
        echo htmlspecialchars($action, ENT_QUOTES, 'UTF-8') . '</li>';
    }

    echo '</ul>';
    echo '<h2>Demo accounts</h2>';
    echo '<table border="0" cellpadding="4" cellspacing="0">';
    echo '<tr><th align="left">Name</th><th align="left">Email</th><th align="left">Password</th><th align="left">Role</th></tr>';

    foreach ($results as $result) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($result['name'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td><code>' . htmlspecialchars($result['email'], ENT_QUOTES, 'UTF-8') . '</code></td>';
        echo '<td><code>' . htmlspecialchars($result['password'], ENT_QUOTES, 'UTF-8') . '</code></td>';
        echo '<td>' . htmlspecialchars($result['role'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '</tr>';
    }

    echo '</table>';
}

// Standalone: open seed.php directly in the browser to re-run demo user seeding.
if (!defined('SEED_FROM_SETUP')) {
    header('Content-Type: text/html; charset=UTF-8');

    try {
        $pdo = require __DIR__ . '/connection.php';
        $results = runSeed($pdo);

        require_once __DIR__ . '/../config/app.php';

        $loginUrl = APP_BASE_PATH . '/auth/login.php';

        echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8" />';
        echo '<title>Database Seed</title>';
        echo '<style>body{font-family:Segoe UI,sans-serif;max-width:40rem;margin:2rem auto;padding:0 1rem;line-height:1.6;}';
        echo 'code{background:#f1f5f9;padding:0.1rem 0.35rem;border-radius:4px;}';
        echo 'ul{padding-left:1.25rem;}</style></head><body>';
        echo '<h1>Seed completed</h1>';
        echo '<p>Demonstration users are ready. Run this page again anytime — existing emails are skipped.</p>';
        renderSeedResults($results);
        echo '<p><a href="' . htmlspecialchars($loginUrl, ENT_QUOTES, 'UTF-8') . '">Go to login</a></p>';
        echo '</body></html>';
    } catch (Throwable $e) {
        http_response_code(500);
        echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8" /><title>Seed Failed</title></head><body>';
        echo '<h1>Seed failed</h1>';
        echo '<p>Run <code>setup.php</code> first to create the database and tables.</p>';
        echo '<pre>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</pre>';
        echo '</body></html>';
    }
}
