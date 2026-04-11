<?php

declare(strict_types=1);

require __DIR__ . '/../../auth/role_check.php';
require_role('admin');
require __DIR__ . '/../../includes/db.php';

$pageTitle = 'Create Case | Lex & Partners';
$assetPathPrefix = '../../';
$publicPathPrefix = '../../public/';
$authPathPrefix = '../../auth/';

$clients = $pdo->query("SELECT id, name FROM users WHERE role = 'client' ORDER BY name ASC")->fetchAll();
$lawyers = $pdo->query("SELECT id, name FROM users WHERE role = 'lawyer' ORDER BY name ASC")->fetchAll();

$formData = [
    'title' => '',
    'description' => '',
    'client_id' => '',
    'lawyer_id' => '',
];
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData['title'] = trim((string) ($_POST['title'] ?? ''));
    $formData['description'] = trim((string) ($_POST['description'] ?? ''));
    $formData['client_id'] = (string) ($_POST['client_id'] ?? '');
    $formData['lawyer_id'] = (string) ($_POST['lawyer_id'] ?? '');

    $clientId = filter_var($formData['client_id'], FILTER_VALIDATE_INT);
    $lawyerId = filter_var($formData['lawyer_id'], FILTER_VALIDATE_INT);
    $clientIds = array_map(static fn(array $client): int => (int) $client['id'], $clients);
    $lawyerIds = array_map(static fn(array $lawyer): int => (int) $lawyer['id'], $lawyers);

    if ($formData['title'] === '' || $formData['description'] === '') {
        $errorMessage = 'Title and description are required.';
    } elseif ($clientId === false || !in_array($clientId, $clientIds, true)) {
        $errorMessage = 'Select a valid client.';
    } elseif ($lawyerId === false || !in_array($lawyerId, $lawyerIds, true)) {
        $errorMessage = 'Select a valid lawyer.';
    } else {
        $stmt = $pdo->prepare(
            "INSERT INTO cases (title, description, status, client_id, lawyer_id)
             VALUES (:title, :description, 'Open', :client_id, :lawyer_id)"
        );
        $stmt->execute([
            'title' => $formData['title'],
            'description' => $formData['description'],
            'client_id' => $clientId,
            'lawyer_id' => $lawyerId,
        ]);

        $successMessage = 'Case created successfully.';
        $formData = [
            'title' => '',
            'description' => '',
            'client_id' => '',
            'lawyer_id' => '',
        ];
    }
}

include '../../includes/header.php';
?>
<main>
  <section class="page-hero">
    <div class="container">
      <h1>Create Case</h1>
      <p>Assign a client and lawyer when opening a new legal matter.</p>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="card admin-form-card">
        <h2>New Case</h2>
        <p>Cases are created with a default status of Open.</p>

        <?php if ($successMessage !== ''): ?>
          <p class="form-success"><?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <?php if ($errorMessage !== ''): ?>
          <p class="form-error"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <?php if (empty($clients) || empty($lawyers)): ?>
          <div class="empty-state">
            Create at least one client and one lawyer before adding a case.
          </div>
        <?php else: ?>
          <form method="post" action="">
            <div class="admin-form-grid">
              <div class="form-group full-width">
                <label for="title">Case Title<span class="required">*</span></label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($formData['title'], ENT_QUOTES, 'UTF-8'); ?>" required />
              </div>

              <div class="form-group">
                <label for="client_id">Client<span class="required">*</span></label>
                <select id="client_id" name="client_id" required>
                  <option value="">Select a client</option>
                  <?php foreach ($clients as $client): ?>
                    <option value="<?php echo (int) $client['id']; ?>"<?php echo $formData['client_id'] === (string) $client['id'] ? ' selected' : ''; ?>>
                      <?php echo htmlspecialchars($client['name'], ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="form-group">
                <label for="lawyer_id">Lawyer<span class="required">*</span></label>
                <select id="lawyer_id" name="lawyer_id" required>
                  <option value="">Select a lawyer</option>
                  <?php foreach ($lawyers as $lawyer): ?>
                    <option value="<?php echo (int) $lawyer['id']; ?>"<?php echo $formData['lawyer_id'] === (string) $lawyer['id'] ? ' selected' : ''; ?>>
                      <?php echo htmlspecialchars($lawyer['name'], ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="form-group full-width">
                <label for="description">Description<span class="required">*</span></label>
                <textarea id="description" name="description" rows="6" required><?php echo htmlspecialchars($formData['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
              </div>
            </div>

            <div class="admin-actions">
              <button type="submit" class="btn">Create Case</button>
              <a class="btn btn-outline" href="<?php echo APP_BASE_PATH; ?>/dashboard/admin/manage_cases.php">Manage Cases</a>
            </div>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </section>
</main>

<?php include '../../includes/footer.php'; ?>
