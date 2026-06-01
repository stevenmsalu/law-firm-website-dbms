<?php

declare(strict_types=1);

require __DIR__ . '/../../auth/role_check.php';
require_role('admin');
$pdo = require __DIR__ . '/../../database/connection.php';

$pageTitle = 'Contact Messages | Admin';
$assetPathPrefix = '../../';
$publicPathPrefix = '../../public/';
$authPathPrefix = '../../auth/';

// Load all contact form submissions, newest first.
$stmt = $pdo->query(
    'SELECT id, name, email, phone, subject, message, created_at
     FROM contacts
     ORDER BY created_at DESC'
);
$enquiries = $stmt->fetchAll();

include '../../includes/header.php';
?>

<main>
  <section class="page-hero">
    <div class="container">
      <h1>Contact Messages</h1>
      <p>View all incoming inquiries from the public contact form.</p>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="admin-actions">
        <a class="btn btn-outline" href="<?php echo APP_BASE_PATH; ?>/dashboard/admin/index.php">Back to Dashboard</a>
      </div>

      <?php if (empty($enquiries)): ?>
        <div class="empty-state">No enquiries yet.</div>
      <?php else: ?>
        <div class="table-wrap">
          <table class="data-table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($enquiries as $enquiry): ?>
                <tr>
                  <td><?php echo htmlspecialchars($enquiry['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars($enquiry['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars($enquiry['subject'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars($enquiry['message'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars(date('M d, Y', strtotime((string) $enquiry['created_at'])), ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php include '../../includes/footer.php'; ?>
