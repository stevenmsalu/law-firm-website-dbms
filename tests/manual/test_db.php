<?php

declare(strict_types=1);

$pdo = require __DIR__ . '/../../config/database.php';

try {
    $stmt = $pdo->query('SELECT id, name, email, role, created_at FROM users ORDER BY id ASC');
    $users = $stmt->fetchAll();
} catch (Throwable $e) {
    http_response_code(500);
    echo '<h1>Database connection failed</h1>';
    echo '<p>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Test</title>
</head>
<body>
    <h1>Database connected successfully</h1>
    <h2>Users</h2>

    <?php if (empty($users)): ?>
        <p>No users found.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($users as $user): ?>
                <li>
                    ID: <?php echo (int) $user['id']; ?> |
                    Name: <?php echo htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8'); ?> |
                    Email: <?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?> |
                    Role: <?php echo htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8'); ?> |
                    Created: <?php echo htmlspecialchars($user['created_at'], ENT_QUOTES, 'UTF-8'); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>

