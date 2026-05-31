<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/app.php';

$pdo = require __DIR__ . '/../database/connection.php';

$stmt = $pdo->query(
    "SELECT name, email
     FROM users
     WHERE role = 'admin'
     LIMIT 1"
);
$admin = $stmt->fetch();
$adminEmail = is_array($admin) ? (string) ($admin['email'] ?? '') : '';
$adminName = is_array($admin) ? (string) ($admin['name'] ?? '') : '';

$pageTitle = 'Account Help | Zimba & Partners';
$assetPathPrefix = '../';
$publicPathPrefix = '../public/';
$authPathPrefix = '';

include '../includes/header.php';
?>
<main class="login-main">
  <section class="login-section">
    <div class="container login-container">
      <div class="card admin-form-card login-card help-card">
        <h2>Need Help Signing In?</h2>
        <p>
          This system does not offer automatic password reset. If you need access or account assistance,
          contact the firm administrator or use the contact form below.
        </p>

        <ul class="checklist">
          <li>You forgot your password</li>
          <li>You need help accessing the system</li>
          <li>You have any other account-related issue</li>
        </ul>

        <p>
          When you reach out, include your <strong>full name</strong> and the <strong>email address</strong>
          registered on your account so your request can be verified.
        </p>

        <?php if ($adminEmail !== ''): ?>
          <p>
            <strong>Administrator contact</strong><br />
            <?php if ($adminName !== ''): ?>
              <?php echo htmlspecialchars($adminName, ENT_QUOTES, 'UTF-8'); ?><br />
            <?php endif; ?>
            <?php echo htmlspecialchars($adminEmail, ENT_QUOTES, 'UTF-8'); ?>
          </p>
        <?php else: ?>
          <p>No administrator account was found in the database. Please use the contact form for assistance.</p>
        <?php endif; ?>

        <div class="admin-actions">
          <a class="btn" href="<?php echo APP_BASE_PATH; ?>/public/contact.php#contact-form">Go to Contact Form</a>
          <a class="btn btn-outline" href="<?php echo APP_BASE_PATH; ?>/auth/login.php">Back to Login</a>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include '../includes/footer.php'; ?>
