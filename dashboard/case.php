<?php

declare(strict_types=1);

require __DIR__ . '/../auth/role_check.php';
require_any_role(['client', 'lawyer']);
require __DIR__ . '/../includes/db.php';

$successMessage = '';
$errorMessage = '';
$allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
$maxFileSize = 5 * 1024 * 1024;
$uploadDirectory = dirname(__DIR__) . '/uploads/';
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
if ($userId !== (int) $case['client_id'] && $userId !== (int) $case['lawyer_id']) {
    http_response_code(403);
    exit('Access denied');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postedCaseId = filter_input(INPUT_POST, 'case_id', FILTER_VALIDATE_INT);
    $messageBody = trim((string) ($_POST['message'] ?? ''));
    $filePath = null;
    $hasAttachment = isset($_FILES['attachment']) && is_array($_FILES['attachment']);

    if ($postedCaseId === false || $postedCaseId === null || $postedCaseId !== (int) $case['id']) {
        $errorMessage = 'Invalid message request.';
    } elseif ($hasAttachment && $_FILES['attachment']['error'] !== UPLOAD_ERR_NO_FILE) {
        $uploadedFile = $_FILES['attachment'];
        $originalName = (string) ($uploadedFile['name'] ?? '');
        $temporaryPath = (string) ($uploadedFile['tmp_name'] ?? '');
        $fileSize = (int) ($uploadedFile['size'] ?? 0);
        $uploadError = (int) ($uploadedFile['error'] ?? UPLOAD_ERR_NO_FILE);
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if ($uploadError !== UPLOAD_ERR_OK) {
            $errorMessage = 'Attachment upload failed.';
        } elseif ($originalName === '' || $temporaryPath === '') {
            $errorMessage = 'Invalid attachment upload.';
        } elseif ($fileSize <= 0 || $fileSize > $maxFileSize) {
            $errorMessage = 'Attachment must be smaller than 5MB.';
        } elseif (!in_array($extension, $allowedExtensions, true)) {
            $errorMessage = 'Only PDF, DOC, DOCX, JPG, JPEG, and PNG files are allowed.';
        } else {
            if (!is_dir($uploadDirectory)) {
                mkdir($uploadDirectory, 0775, true);
            }

            if (!is_writable($uploadDirectory)) {
                $errorMessage = 'Upload directory is not writable.';
            } else {
                $safeBaseName = preg_replace('/[^A-Za-z0-9._-]/', '_', basename($originalName));
                $storedFileName = time() . '_' . $safeBaseName;
                $destinationPath = $uploadDirectory . $storedFileName;

                if (!move_uploaded_file($temporaryPath, $destinationPath)) {
                    $errorMessage = 'Failed to store the attachment.';
                } else {
                    $filePath = 'uploads/' . $storedFileName;
                }
            }
        }
    }

    if ($errorMessage === '' && $messageBody === '' && $filePath === null) {
        $errorMessage = 'Add a message or choose a file before sending.';
    }

    if ($errorMessage === '') {
        $insertStmt = $pdo->prepare(
            'INSERT INTO messages (case_id, sender_id, message, file_path)
             VALUES (:case_id, :sender_id, :message, :file_path)'
        );
        $insertStmt->execute([
            'case_id' => (int) $case['id'],
            'sender_id' => $userId,
            'message' => $messageBody,
            'file_path' => $filePath,
        ]);

        header('Location: ' . APP_BASE_PATH . '/dashboard/case.php?id=' . (int) $case['id'] . '&sent=1');
        exit;
    }
}

if (isset($_GET['sent']) && $_GET['sent'] === '1') {
    $successMessage = 'Message sent successfully.';
}

$messageStmt = $pdo->prepare(
    "SELECT
        messages.id,
        messages.message,
        messages.file_path,
        messages.created_at,
        messages.sender_id,
        users.name
     FROM messages
     JOIN users ON users.id = messages.sender_id
     WHERE messages.case_id = :case_id
     ORDER BY messages.created_at ASC"
);
$messageStmt->execute(['case_id' => (int) $case['id']]);
$messages = $messageStmt->fetchAll();

$pageTitle = 'Case Details | Zimba & Partners';
$assetPathPrefix = '../';
$publicPathPrefix = '../public/';
$authPathPrefix = '../auth/';

include '../includes/header.php';
?>
<main>
  <section class="page-hero">
    <div class="container">
      <h1><?php echo htmlspecialchars($case['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
      <p>Review the full matter summary and assignment details below.</p>
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
            <li>Status: <?php echo htmlspecialchars($case['status'], ENT_QUOTES, 'UTF-8'); ?></li>
            <li>Client: <?php echo htmlspecialchars((string) ($case['client_name'] ?? 'Unassigned'), ENT_QUOTES, 'UTF-8'); ?></li>
            <li>Lawyer: <?php echo htmlspecialchars((string) ($case['lawyer_name'] ?? 'Unassigned'), ENT_QUOTES, 'UTF-8'); ?></li>
            <li>Created: <?php echo htmlspecialchars(date('M d, Y', strtotime((string) $case['created_at'])), ENT_QUOTES, 'UTF-8'); ?></li>
          </ul>
          <div class="admin-actions">
            <?php if (($_SESSION['role'] ?? '') === 'client'): ?>
              <a class="btn btn-outline" href="<?php echo APP_BASE_PATH; ?>/dashboard/client.php">Back to Client Dashboard</a>
            <?php else: ?>
              <a class="btn btn-outline" href="<?php echo APP_BASE_PATH; ?>/dashboard/lawyer.php">Back to Lawyer Dashboard</a>
            <?php endif; ?>
          </div>
        </aside>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="card">
        <div class="section-header align-left">
          <h2>Case Messages</h2>
          <p>Clients and assigned lawyers can exchange updates directly within this case.</p>
        </div>

        <?php if ($successMessage !== ''): ?>
          <p class="form-success"><?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <?php if ($errorMessage !== ''): ?>
          <p class="form-error"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <?php if (empty($messages)): ?>
          <div class="empty-state">No messages yet. Start the conversation below.</div>
        <?php else: ?>
          <div class="message-thread">
            <?php foreach ($messages as $message): ?>
              <?php $isCurrentUser = (int) $message['sender_id'] === $userId; ?>
              <article class="message-bubble<?php echo $isCurrentUser ? ' is-own' : ''; ?>">
                <div class="message-meta">
                  <strong><?php echo htmlspecialchars($message['name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                  <span><?php echo htmlspecialchars(date('M d, Y g:i A', strtotime((string) $message['created_at'])), ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
                <?php if ((string) $message['message'] !== ''): ?>
                  <p><?php echo nl2br(htmlspecialchars((string) $message['message'], ENT_QUOTES, 'UTF-8')); ?></p>
                <?php endif; ?>
                <?php if (!empty($message['file_path'])): ?>
                  <?php $attachmentName = basename((string) $message['file_path']); ?>
                  <p class="message-attachment">
                    <a class="card-link" href="<?php echo APP_BASE_PATH . '/' . htmlspecialchars((string) $message['file_path'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">
                      <?php echo htmlspecialchars('Attachment: ' . $attachmentName, ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                  </p>
                <?php endif; ?>
              </article>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <form method="post" action="" enctype="multipart/form-data">
          <input type="hidden" name="case_id" value="<?php echo (int) $case['id']; ?>" />
          <div class="form-group">
            <label for="message">Send a Message</label>
            <textarea id="message" name="message" rows="5"></textarea>
          </div>
          <div class="form-group">
            <label for="attachment">Attachment</label>
            <input type="file" id="attachment" name="attachment" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" />
          </div>
          <div class="admin-actions">
            <button type="submit" class="btn">Send Message</button>
          </div>
        </form>
      </div>
    </div>
  </section>
</main>

<?php include '../includes/footer.php'; ?>
