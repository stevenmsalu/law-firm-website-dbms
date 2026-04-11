<?php
// dashboard/admin/index.php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /law-firm-website-dbms/auth/login.php');
    exit;
}

// Check if user is admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: /law-firm-website-dbms/auth/login.php');
    exit;
}

// If we get here, user is authenticated as admin
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Law Firm Management</title>
    <link rel="stylesheet" href="/law-firm-website-dbms/assets/css/style.css">
</head>
<body>
<div style="padding: 20px;">
    <h1>Admin Dashboard</h1>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['name'] ?? 'Admin', ENT_QUOTES, 'UTF-8'); ?>!</p>

    <h2>Admin Menu</h2>
    <ul>
        <li><a href="/law-firm-website-dbms/dashboard/admin/create_user.php">Create User</a></li>
        <li><a href="/law-firm-website-dbms/dashboard/admin/create_case.php">Create Case</a></li>
        <li><a href="/law-firm-website-dbms/dashboard/admin/manage_cases.php">Manage Cases</a></li>
    </ul>

    <p><a href="/law-firm-website-dbms/auth/logout.php">Logout</a></p>
</div>
</body>
</html>