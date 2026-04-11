<?php

declare(strict_types=1);

require __DIR__ . '/../auth/role_check.php';
require_role('client');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard</title>
</head>
<body>
    <h1>Client Dashboard</h1>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>.</p>
    <p><a href="<?php echo APP_BASE_PATH; ?>/auth/logout.php">Logout</a></p>
</body>
</html>
