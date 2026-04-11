<?php

declare(strict_types=1);

require __DIR__ . '/../auth/role_check.php';
require_role('client');
require __DIR__ . '/../includes/db.php';

$pageTitle = 'Client Dashboard | Lex & Partners';
$assetPathPrefix = '../';
$publicPathPrefix = '../public/';
$authPathPrefix = '../auth/';

$stmt = $pdo->prepare(
    "SELECT
        cases.id,
        cases.title,
        cases.status,
        cases.created_at,
        lawyer.name AS lawyer_name
     FROM cases
     LEFT JOIN users AS lawyer ON lawyer.id = cases.lawyer_id
     WHERE cases.client_id = :client_id
     ORDER BY cases.created_at DESC"
);
$stmt->execute(['client_id' => (int) $_SESSION['user_id']]);
$cases = $stmt->fetchAll();

include '../includes/header.php';
?>
<main>
  <section class="page-hero">
    <div class="container">
      <h1>Client Dashboard</h1>
      <p>Welcome, <?php echo htmlspecialchars((string) ($_SESSION['name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>. Review all cases assigned to you.</p>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="section-header align-left">
        <h2>Your Cases</h2>
        <p>Track current status, assigned counsel, and open each case for full details.</p>
      </div>

      <div class="admin-actions">
        <a class="btn btn-outline" href="<?php echo APP_BASE_PATH; ?>/auth/logout.php">Logout</a>
      </div>

      <?php if (empty($cases)): ?>
        <div class="empty-state">No cases have been assigned to your account yet.</div>
      <?php else: ?>
        <div class="table-wrap">
          <table class="data-table">
            <thead>
              <tr>
                <th>Case Title</th>
                <th>Status</th>
                <th>Assigned Lawyer</th>
                <th>Created Date</th>
                <th>View</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($cases as $case): ?>
                <tr>
                  <td><strong><?php echo htmlspecialchars($case['title'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                  <td><span class="status-pill"><?php echo htmlspecialchars($case['status'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                  <td><?php echo htmlspecialchars((string) ($case['lawyer_name'] ?? 'Unassigned'), ENT_QUOTES, 'UTF-8'); ?></td>
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

<?php include '../includes/footer.php'; ?>
