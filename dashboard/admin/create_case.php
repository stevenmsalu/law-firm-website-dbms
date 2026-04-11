<?php

declare(strict_types=1);

require __DIR__ . '/../../auth/role_check.php';
require_role('admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Case</title>
</head>
<body>
    <h1>Create Case</h1>
    <p>Admin only page.</p>
    <p><a href="<?php echo APP_BASE_PATH; ?>/dashboard/admin/index.php">Back to Admin Dashboard</a></p>
    <p><a href="<?php echo APP_BASE_PATH; ?>/auth/logout.php">Logout</a></p>
</body>
</html>
