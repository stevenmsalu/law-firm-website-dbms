<?php
// test_redirect.php
session_start();

echo "<h1>Redirect Test</h1>";
echo "Session ID: " . session_id() . "<br>";
echo "<pre>";
echo "Session data:\n";
print_r($_SESSION);
echo "</pre>";

if (isset($_SESSION['user_id'])) {
    echo "<p style='color:green'>✅ You are logged in as: " . ($_SESSION['name'] ?? 'Unknown') . "</p>";
    echo "<a href='/law-firm-website-dbms/dashboard/admin/index.php'>Try going to Admin Dashboard (with session check)</a><br>";
    echo "<a href='/law-firm-website-dbms/dashboard/admin/index.php?debug=1'>Try with debug parameter</a>";
} else {
    echo "<p style='color:red'>❌ No user_id in session</p>";
    echo "<a href='/law-firm-website-dbms/auth/login.php'>Go to Login</a>";
}

// Manually set session for testing
if (isset($_GET['force_login'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['role'] = 'admin';
    $_SESSION['name'] = 'Admin';
    echo "<p style='color:green'>Forced login! Session set.</p>";
    echo "<meta http-equiv='refresh' content='2'>";
}
?>
