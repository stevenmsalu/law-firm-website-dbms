<?php
// auth/test_full_login.php - Tests the complete login process
session_start();
require_once __DIR__ . '/../../config/database.php';

$message = '';

// Handle login attempt
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT id, name, email, password, role FROM users WHERE email = :email');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];

        $message = '<span style="color:green">✅ Login successful! Session created.</span>';

        // Show session data
        $message .= '<br>Session data: <pre>';
        $message .= print_r($_SESSION, true);
        $message .= '</pre>';

        // Add redirect button
        $message .= '<br><a href="/law-firm-website-dbms/dashboard/admin/index.php" style="display:inline-block;margin-top:10px;padding:10px;background:#1f3f75;color:white;text-decoration:none;border-radius:4px;">Click here to go to Admin Dashboard</a>';
    } else {
        $message = '<span style="color:red">❌ Invalid credentials</span>';
    }
}

// Check if already logged in
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Full Login Flow</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .container { max-width: 500px; margin: 0 auto; }
        input { width: 100%; padding: 8px; margin: 5px 0 15px; }
        button { background: #1f3f75; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        .info { background: #f0f0f0; padding: 10px; margin: 10px 0; border-radius: 4px; }
        .success { background: #d4edda; color: #155724; padding: 10px; margin: 10px 0; border-radius: 4px; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; margin: 10px 0; border-radius: 4px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Test Full Login Flow</h1>

    <?php if ($is_logged_in): ?>
        <div class="success">
            <strong>You are already logged in!</strong><br>
            Session ID: <?php echo session_id(); ?><br>
            <a href="/law-firm-website-dbms/dashboard/admin/index.php">Go to Admin Dashboard</a><br>
            <a href="/law-firm-website-dbms/auth/logout.php">Logout</a>
        </div>
    <?php endif; ?>

    <?php if ($message): ?>
        <div class="<?php echo strpos($message, '✅') !== false ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <div>
            <label>Email:</label>
            <input type="email" name="email" value="admin@lawfirm.com" required>
        </div>
        <div>
            <label>Password:</label>
            <input type="password" name="password" value="admin123" required>
        </div>
        <button type="submit">Login</button>
    </form>

    <div class="info">
        <strong>Debug Info:</strong><br>
        Session ID: <?php echo session_id(); ?><br>
        Session status: <?php echo session_status(); ?><br>
        Cookie session: <?php echo ini_get('session.use_cookies'); ?><br>
    </div>
</div>
</body>
</html>
