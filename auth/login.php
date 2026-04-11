<?php
// auth/login.php
// http://localhost/law-firm-website-dbms/auth/login.php
declare(strict_types=1);

require_once __DIR__ . '/../config/app.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If already logged in, redirect to appropriate dashboard
if (!empty($_SESSION['user_id']) && !empty($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: ' . APP_BASE_PATH . '/dashboard/admin/index.php');
        exit;
    }
    if ($_SESSION['role'] === 'lawyer') {
        header('Location: ' . APP_BASE_PATH . '/dashboard/lawyer.php');
        exit;
    }
    if ($_SESSION['role'] === 'client') {
        header('Location: ' . APP_BASE_PATH . '/dashboard/client.php');
        exit;
    }
}

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $plainPassword = $_POST['password'] ?? '';

    if ($email === '' || $plainPassword === '') {
        $error = 'Please enter both email and password.';
    } else {
        require_once __DIR__ . '/../includes/db.php';

        $stmt = $pdo->prepare('SELECT id, name, email, password, role FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($plainPassword, $user['password'])) {
            session_regenerate_id(true);

            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];

            if ($user['role'] === 'admin') {
                header('Location: ' . APP_BASE_PATH . '/dashboard/admin/index.php');
                exit;
            }
            if ($user['role'] === 'lawyer') {
                header('Location: ' . APP_BASE_PATH . '/dashboard/lawyer.php');
                exit;
            }
            if ($user['role'] === 'client') {
                header('Location: ' . APP_BASE_PATH . '/dashboard/client.php');
                exit;
            }

            $error = 'Invalid user role';
            session_unset();
            session_destroy();
        } else {
            $error = 'Invalid credentials';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Law Firm Management</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f4f6fa;
            font-family: Arial, sans-serif;
        }

        .login-box {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 24px;
        }

        .login-box h1 {
            margin: 0 0 16px;
            font-size: 24px;
            color: #1b1f24;
        }

        .field {
            margin-bottom: 14px;
        }

        .field label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .field input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccd3dc;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .error {
            margin-bottom: 12px;
            color: #b00020;
            background: #fdecee;
            border: 1px solid #f7c6cd;
            border-radius: 6px;
            padding: 10px;
            font-size: 14px;
        }

        .actions button {
            width: 100%;
            border: 0;
            border-radius: 6px;
            padding: 11px 14px;
            font-size: 15px;
            font-weight: 600;
            color: #fff;
            background: #1f3f75;
            cursor: pointer;
        }

        .actions button:hover {
            background: #0f2a5a;
        }
    </style>
</head>
<body>
<div class="login-box">
    <h1>Sign in</h1>

    <?php if ($error !== ''): ?>
        <div class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="actions">
            <button type="submit">Login</button>
        </div>
    </form>
</div>
</body>
</html>
