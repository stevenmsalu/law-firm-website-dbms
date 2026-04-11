<?php
// check_path.php
require_once __DIR__ . '/../../config/app.php';

echo "<h1>APP_BASE_PATH Debug</h1>";
echo "APP_BASE_PATH: '" . APP_BASE_PATH . "'<br>";
echo "Current script: " . $_SERVER['SCRIPT_NAME'] . "<br>";
echo "Document root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Full URL: " . (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "<br>";

// Test what the redirect URLs would be
echo "<h3>Redirect URLs would be:</h3>";
echo "Admin dashboard: " . APP_BASE_PATH . "/dashboard/admin/index.php<br>";
echo "Login redirect: " . APP_BASE_PATH . "/auth/login.php<br>";

// Check if the paths exist
$admin_path = $_SERVER['DOCUMENT_ROOT'] . APP_BASE_PATH . "/dashboard/admin/index.php";
echo "<br>Admin file exists at: " . $admin_path . "<br>";
echo "File exists: " . (file_exists($admin_path) ? "✅ Yes" : "❌ No");
?>
