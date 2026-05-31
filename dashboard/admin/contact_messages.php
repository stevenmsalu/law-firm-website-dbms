<?php
declare(strict_types=1);

require __DIR__ . '/../../auth/role_check.php';
require_role('admin');
$pdo = require __DIR__ . '/../../database/connection.php';

$pageTitle = 'Contact Messages | Admin';
$assetPathPrefix = '../../';
$publicPathPrefix = '../../public/';
$authPathPrefix = '../../auth/';

$stmt = $pdo->query(
    "SELECT * FROM contacts ORDER BY created_at DESC"
);
$messages = $stmt->fetchAll();

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

                <?php if (empty($messages)): ?>
                    <div class="empty-state">No messages yet.</div>
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
                            <?php foreach ($messages as $msg): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($msg['name']); ?></td>
                                    <td><?php echo htmlspecialchars($msg['email']); ?></td>
                                    <td><?php echo htmlspecialchars($msg['subject']); ?></td>
                                    <td><?php echo htmlspecialchars($msg['message']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($msg['created_at'])); ?></td>
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