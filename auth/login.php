<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/app.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Send the user to the dashboard that matches their role.
 */
function redirectToRoleDashboard(string $role): void
{
    if ($role === 'admin') {
        header('Location: ' . APP_BASE_PATH . '/dashboard/admin/index.php');
        exit;
    }

    if ($role === 'lawyer') {
        header('Location: ' . APP_BASE_PATH . '/dashboard/lawyer.php');
        exit;
    }

    if ($role === 'client') {
        header('Location: ' . APP_BASE_PATH . '/dashboard/client.php');
        exit;
    }
}

// Already logged in — go straight to the correct dashboard.
if (!empty($_SESSION['user_id']) && !empty($_SESSION['role'])) {
    redirectToRoleDashboard((string) $_SESSION['role']);
}

$errorMessage = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string) ($_POST['email'] ?? ''));
    $plainPassword = (string) ($_POST['password'] ?? '');

    if ($email === '' || $plainPassword === '') {
        $errorMessage = 'Please enter both email and password.';
    } else {
        $pdo = require __DIR__ . '/../database/connection.php';

        $stmt = $pdo->prepare('SELECT id, name, email, password, role FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($plainPassword, $user['password'])) {
            session_regenerate_id(true);

            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];

            $userRole = (string) $user['role'];

            // Only admin, lawyer, and client accounts can sign in.
            if (in_array($userRole, ['admin', 'lawyer', 'client'], true)) {
                redirectToRoleDashboard($userRole);
            }

            $errorMessage = 'Invalid user role';
            session_unset();
            session_destroy();
        } else {
            $errorMessage = 'Invalid credentials';
        }
    }
}

$pageTitle = 'Login | Zimba & Partners';
$assetPathPrefix = '../';
$publicPathPrefix = '../public/';
$authPathPrefix = '';

include '../includes/header.php';
?>
<main class="login-main">
  <section class="login-section">
    <div class="container login-container">
      <div class="card admin-form-card login-card">
        <h2>Sign In</h2>
        <p>Use the email address and password assigned to your account.</p>

        <?php if ($errorMessage !== ''): ?>
          <p class="form-error"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <form method="post" action="">
          <div class="form-group">
            <label for="email">Email<span class="required">*</span></label>
            <input
              type="email"
              id="email"
              name="email"
              value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>"
              required
            />
          </div>

          <div class="form-group">
            <label for="password">Password<span class="required">*</span></label>
            <input type="password" id="password" name="password" required />
          </div>

          <div class="admin-actions">
            <button type="submit" class="btn">Login</button>
            <a class="btn btn-outline" href="<?php echo APP_BASE_PATH; ?>/auth/help.php">Need Help?</a>
          </div>
        </form>
      </div>
    </div>
  </section>
</main>

<?php include '../includes/footer.php'; ?>
