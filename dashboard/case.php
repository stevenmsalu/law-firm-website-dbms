<?php

declare(strict_types=1);

require_once __DIR__ . '/../auth/role_check.php';

$role = (string) ($_SESSION['role'] ?? '');

if ($role === 'admin') {
    require_role('admin');
} else {
    require_any_role(['client', 'lawyer']);
}

$pdo = require __DIR__ . '/../database/connection.php';

$caseId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($caseId === false || $caseId === null) {
    http_response_code(400);
    exit('Invalid case ID.');
}

$stmt = $pdo->prepare(
    "SELECT
        cases.id,
        cases.title,
        cases.description,
        cases.status,
        cases.created_at,
        cases.client_id,
        cases.lawyer_id,
        client.name AS client_name,
        lawyer.name AS lawyer_name
     FROM cases
     LEFT JOIN users AS client ON client.id = cases.client_id
     LEFT JOIN users AS lawyer ON lawyer.id = cases.lawyer_id
     WHERE cases.id = :id
     LIMIT 1"
);
$stmt->execute(['id' => $caseId]);
$case = $stmt->fetch();

if (!$case) {
    http_response_code(404);
    exit('Case not found.');
}

$userId = (int) $_SESSION['user_id'];

if ($role !== 'admin') {
    if ($userId !== (int) $case['client_id'] && $userId !== (int) $case['lawyer_id']) {
        http_response_code(403);
        exit('Access denied');
    }
}

$pageTitle = $role === 'admin' ? 'Case Summary | Admin' : 'Case Details | Zimba & Partners';
$assetPathPrefix = '../';
$publicPathPrefix = '../public/';
$authPathPrefix = '../auth/';

include '../includes/header.php';
?>
<main>
  <section class="page-hero">
    <div class="container">
      <h1><?php echo htmlspecialchars($case['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
      <p>
        <?php if ($role === 'admin'): ?>
          Administrator case summary — assignment and status details only.
        <?php else: ?>
          Review the full matter summary and assignment details below.
        <?php endif; ?>
      </p>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="two-column">
        <article class="card">
          <h2>Case Summary</h2>
          <p><?php echo nl2br(htmlspecialchars((string) $case['description'], ENT_QUOTES, 'UTF-8')); ?></p>
        </article>

        <aside class="highlight-panel">
          <h2>Case Information</h2>
          <ul class="checklist">
            <?php if ($role === 'admin'): ?>
              <li>Case ID: <?php echo (int) $case['id']; ?></li>
            <?php endif; ?>
            <li>Status: <span class="status-pill"><?php echo htmlspecialchars($case['status'], ENT_QUOTES, 'UTF-8'); ?></span></li>
            <li>Client: <?php echo htmlspecialchars((string) ($case['client_name'] ?? 'Unassigned'), ENT_QUOTES, 'UTF-8'); ?></li>
            <li>Lawyer: <?php echo htmlspecialchars((string) ($case['lawyer_name'] ?? 'Unassigned'), ENT_QUOTES, 'UTF-8'); ?></li>
            <li>Created: <?php echo htmlspecialchars(date('M d, Y g:i A', strtotime((string) $case['created_at'])), ENT_QUOTES, 'UTF-8'); ?></li>
          </ul>
          <div class="admin-actions">
            <?php if ($role === 'admin'): ?>
              <a class="btn btn-outline" href="<?php echo APP_BASE_PATH; ?>/dashboard/admin/manage_cases.php">Back to Manage Cases</a>
              <a class="btn btn-outline" href="<?php echo APP_BASE_PATH; ?>/dashboard/admin/index.php">Admin Dashboard</a>
            <?php elseif ($role === 'client'): ?>
              <a class="btn btn-outline" href="<?php echo APP_BASE_PATH; ?>/dashboard/client.php">Back to Client Dashboard</a>
            <?php else: ?>
              <a class="btn btn-outline" href="<?php echo APP_BASE_PATH; ?>/dashboard/lawyer.php">Back to Lawyer Dashboard</a>
            <?php endif; ?>
          </div>
        </aside>
      </div>
    </div>
  </section>
</main>

<?php include '../includes/footer.php'; ?>
