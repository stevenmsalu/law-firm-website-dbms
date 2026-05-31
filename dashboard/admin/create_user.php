<?php

declare(strict_types=1);

require __DIR__ . '/../../auth/role_check.php';
require_role('admin');
$pdo = require __DIR__ . '/../../database/connection.php';

$pageTitle = 'Create User | Zimba & Partners';
$assetPathPrefix = '../../';
$publicPathPrefix = '../../public/';
$authPathPrefix = '../../auth/';

$formData = [
    'name' => '',
    'email' => '',
    'role' => 'lawyer',
];
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData['name'] = trim((string) ($_POST['name'] ?? ''));
    $formData['email'] = trim((string) ($_POST['email'] ?? ''));
    $plainPassword = (string) ($_POST['password'] ?? '');
    $formData['role'] = (string) ($_POST['role'] ?? 'lawyer');

    if ($formData['name'] === '' || $formData['email'] === '' || $plainPassword === '') {
        $errorMessage = 'All fields are required.';
    } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errorMessage = 'Enter a valid email address.';
    } elseif (strlen($plainPassword) < 6) {
        $errorMessage = 'Password must be at least 6 characters.';
    } elseif (!in_array($formData['role'], ['lawyer', 'client'], true)) {
        $errorMessage = 'Select a valid user role.';
    } else {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO users (name, email, password, role)
                 VALUES (:name, :email, :password, :role)'
            );
            $stmt->execute([
                'name' => $formData['name'],
                'email' => $formData['email'],
                'password' => password_hash($plainPassword, PASSWORD_DEFAULT),
                'role' => $formData['role'],
            ]);

            $successMessage = 'User created successfully.';
            $formData = [
                'name' => '',
                'email' => '',
                'role' => 'lawyer',
            ];
        } catch (PDOException $exception) {
            $errorMessage = $exception->getCode() === '23000'
                ? 'A user with that email already exists.'
                : 'Unable to create the user right now.';
        }
    }
}

include '../../includes/header.php';
?>
<main>
  <section class="page-hero">
    <div class="container">
      <h1>Create User</h1>
      <p>Add new lawyers and clients from the admin area.</p>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="card admin-form-card">
        <h2>New User</h2>
        <p>Only lawyers and clients can be created from this form. Admin access remains restricted.</p>

        <?php if ($successMessage !== ''): ?>
          <p class="form-success"><?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <?php if ($errorMessage !== ''): ?>
          <p class="form-error"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <form method="post" action="">
          <div class="admin-form-grid">
            <div class="form-group">
              <label for="name">Name<span class="required">*</span></label>
              <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($formData['name'], ENT_QUOTES, 'UTF-8'); ?>" required />
            </div>

            <div class="form-group">
              <label for="email">Email<span class="required">*</span></label>
              <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($formData['email'], ENT_QUOTES, 'UTF-8'); ?>" required />
            </div>

            <div class="form-group">
              <label for="password">Password<span class="required">*</span></label>
              <input type="password" id="password" name="password" required />
            </div>

            <div class="form-group">
              <label for="role">Role<span class="required">*</span></label>
              <select id="role" name="role" required>
                <option value="lawyer"<?php echo $formData['role'] === 'lawyer' ? ' selected' : ''; ?>>Lawyer</option>
                <option value="client"<?php echo $formData['role'] === 'client' ? ' selected' : ''; ?>>Client</option>
              </select>
            </div>
          </div>

          <div class="admin-actions">
            <button type="submit" class="btn">Create User</button>
            <a class="btn btn-outline" href="<?php echo APP_BASE_PATH; ?>/dashboard/admin/index.php">Back to Dashboard</a>
          </div>
        </form>
      </div>
    </div>
  </section>
</main>

<?php include '../../includes/footer.php'; ?>
