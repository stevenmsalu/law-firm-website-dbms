<?php

declare(strict_types=1);

require __DIR__ . '/../../auth/role_check.php';
require_role('admin');
$pdo = require __DIR__ . '/../../database/connection.php';

$userCount = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$caseCount = (int) $pdo->query('SELECT COUNT(*) FROM cases')->fetchColumn();

$pageTitle = 'Admin Dashboard | Zimba & Partners';
$assetPathPrefix = '../../';
$publicPathPrefix = '../../public/';
$authPathPrefix = '../../auth/';

include '../../includes/header.php';
?>
<main>
    <section class="page-hero">
        <div class="container">
            <h1>Admin Dashboard</h1>
            <p>Welcome back, <?php echo htmlspecialchars((string) ($_SESSION['name'] ?? 'Admin'), ENT_QUOTES, 'UTF-8'); ?>.</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="admin-grid">
                <article class="admin-stat">
                    <span class="admin-stat-label">Total Users</span>
                    <span class="admin-stat-value"><?php echo $userCount; ?></span>
                </article>
                <article class="admin-stat">
                    <span class="admin-stat-label">Total Cases</span>
                    <span class="admin-stat-value"><?php echo $caseCount; ?></span>
                </article>
                <article class="admin-stat">
                    <span class="admin-stat-label">Signed In As</span>
                    <span class="admin-stat-value"><?php echo htmlspecialchars((string) ($_SESSION['role'] ?? 'admin'), ENT_QUOTES, 'UTF-8'); ?></span>
                </article>
            </div>

            <section class="section">
                <div class="highlight-panel">
                    <h2>Admin Actions</h2>
                    <p>Manage users and case assignments from one place while keeping the public site styling consistent.</p>
                    <div class="admin-actions">
                        <a class="btn" href="<?php echo APP_BASE_PATH; ?>/dashboard/admin/create_user.php">Create User</a>
                        <a class="btn btn-outline" href="<?php echo APP_BASE_PATH; ?>/dashboard/admin/create_case.php">Create Case</a>
                        <a class="btn btn-outline" href="<?php echo APP_BASE_PATH; ?>/dashboard/admin/manage_cases.php">Manage Cases</a>
                        <!-- ➕ STEP 5 — Added View Contacts button -->
                        <a class="btn btn-outline" href="<?php echo APP_BASE_PATH; ?>/dashboard/admin/contact_messages.php">View Contacts</a>
                        <a class="btn btn-outline" href="<?php echo APP_BASE_PATH; ?>/auth/logout.php">Logout</a>
                    </div>
                </div>
            </section>
        </div>
    </section>
</main>

<?php include '../../includes/footer.php'; ?>