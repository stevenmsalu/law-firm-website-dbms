<?php

declare(strict_types=1);

require __DIR__ . '/../auth/role_check.php';
require_role('lawyer');
require __DIR__ . '/../includes/db.php';

$pageTitle = 'Lawyer Dashboard | Lex & Partners';
$assetPathPrefix = '../';
$publicPathPrefix = '../public/';
$authPathPrefix = '../auth/';

$successMessage = '';
$errorMessage = '';
$allowedStatuses = ['Open', 'In Progress', 'Closed'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $caseId = filter_input(INPUT_POST, 'case_id', FILTER_VALIDATE_INT);
    $status = trim((string) ($_POST['status'] ?? ''));

    if ($caseId === false || $caseId === null || !in_array($status, $allowedStatuses, true)) {
        $errorMessage = 'Invalid case update request.';
    } else {
        $updateStmt = $pdo->prepare(
            'UPDATE cases
             SET status = :status
             WHERE id = :id AND lawyer_id = :lawyer_id'
        );
        $updateStmt->execute([
            'status' => $status,
            'id' => $caseId,
            'lawyer_id' => (int) $_SESSION['user_id'],
        ]);

        if ($updateStmt->rowCount() > 0) {
            header('Location: ' . APP_BASE_PATH . '/dashboard/lawyer.php?updated=1');
            exit;
        }

        $errorMessage = 'Case status could not be updated.';
    }
}

if (isset($_GET['updated']) && $_GET['updated'] === '1') {
    $successMessage = 'Case status updated successfully.';
}

$stmt = $pdo->prepare(
    "SELECT
        cases.id,
        cases.title,
        cases.status,
        cases.created_at,
        client.name AS client_name
     FROM cases
     LEFT JOIN users AS client ON client.id = cases.client_id
     WHERE cases.lawyer_id = :lawyer_id
     ORDER BY cases.created_at DESC"
);
$stmt->execute(['lawyer_id' => (int) $_SESSION['user_id']]);
$cases = $stmt->fetchAll();

include '../includes/header.php';
?>
<main>
  <section class="page-hero">
    <div class="container">
      <h1>Lawyer Dashboard</h1>
      <p>Welcome, <?php echo htmlspecialchars((string) ($_SESSION['name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>. Manage your assigned cases and update their status.</p>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="section-header align-left">
        <h2>Your Assigned Cases</h2>
        <p>Review each matter, keep statuses current, and open case details when needed.</p>
      </div>

      <div class="admin-actions">
        <a class="btn btn-outline" href="<?php echo APP_BASE_PATH; ?>/auth/logout.php">Logout</a>
      </div>

      <?php if ($successMessage !== ''): ?>
        <p class="form-success"><?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?></p>
      <?php endif; ?>

      <?php if ($errorMessage !== ''): ?>
        <p class="form-error"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></p>
      <?php endif; ?>

      <?php if (empty($cases)): ?>
        <div class="empty-state">No cases are assigned to your account yet.</div>
      <?php else: ?>
        <div class="table-wrap">
          <table class="data-table">
            <thead>
              <tr>
                <th>Case Title</th>
                <th>Client Name</th>
                <th>Status</th>
                <th>Created Date</th>
                <th>Update Status</th>
                <th>View</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($cases as $case): ?>
                <tr>
                  <td><strong><?php echo htmlspecialchars($case['title'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                  <td><?php echo htmlspecialchars((string) ($case['client_name'] ?? 'Unassigned'), ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><span class="status-pill"><?php echo htmlspecialchars($case['status'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                  <td><?php echo htmlspecialchars(date('M d, Y', strtotime((string) $case['created_at'])), ENT_QUOTES, 'UTF-8'); ?></td>
                  <td>
                    <form method="post" action="">
                      <input type="hidden" name="case_id" value="<?php echo (int) $case['id']; ?>" />
                      <div class="admin-actions">
                        <select name="status" aria-label="Update case status">
                          <?php foreach ($allowedStatuses as $status): ?>
                            <option value="<?php echo htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?>"<?php echo $case['status'] === $status ? ' selected' : ''; ?>>
                              <?php echo htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-small">Save</button>
                      </div>
                    </form>
                  </td>
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
