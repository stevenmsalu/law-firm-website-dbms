<?php

declare(strict_types=1);

require __DIR__ . '/../../auth/role_check.php';
require_role('admin');
require __DIR__ . '/../../includes/db.php';

$pageTitle = 'Manage Cases | Zimba & Partners';
$assetPathPrefix = '../../';
$publicPathPrefix = '../../public/';
$authPathPrefix = '../../auth/';

$stmt = $pdo->query(
    "SELECT
        cases.id,
        cases.title,
        cases.status,
        cases.created_at,
        client.name AS client_name,
        lawyer.name AS lawyer_name
     FROM cases
     LEFT JOIN users AS client ON client.id = cases.client_id
     LEFT JOIN users AS lawyer ON lawyer.id = cases.lawyer_id
     ORDER BY cases.created_at DESC"
);
$cases = $stmt->fetchAll();

include '../../includes/header.php';
?>
<main>
  <section class="page-hero">
    <div class="container">
      <h1>Manage Cases</h1>
      <p>Review all assigned matters and track current status.</p>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="section-header align-left">
        <h2>All Cases</h2>
        <p>Client and lawyer assignments are loaded directly from the database using joined queries.</p>
      </div>

      <div class="admin-actions">
        <a class="btn" href="<?php echo APP_BASE_PATH; ?>/dashboard/admin/create_case.php">Create Case</a>
        <a class="btn btn-outline" href="<?php echo APP_BASE_PATH; ?>/dashboard/admin/index.php">Back to Dashboard</a>
      </div>

      <?php if (empty($cases)): ?>
        <div class="empty-state">
          No cases have been created yet.
        </div>
      <?php else: ?>
        <div class="table-wrap">
          <table class="data-table">
            <thead>
              <tr>
                <th>Case Title</th>
                <th>Client Name</th>
                <th>Lawyer Name</th>
                <th>Status</th>
                <th>Created Date</th>
                <th>Details</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($cases as $case): ?>
                <tr>
                  <td><strong><?php echo htmlspecialchars($case['title'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                  <td><?php echo htmlspecialchars((string) ($case['client_name'] ?? 'Unassigned'), ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars((string) ($case['lawyer_name'] ?? 'Unassigned'), ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><span class="status-pill"><?php echo htmlspecialchars($case['status'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                  <td><?php echo htmlspecialchars(date('M d, Y', strtotime((string) $case['created_at'])), ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><a class="card-link" href="<?php echo APP_BASE_PATH; ?>/dashboard/case.php?id=<?php echo (int) $case['id']; ?>">View</a></td>
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
