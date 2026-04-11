<?php
// debug_login.php - DELETE AFTER DEBUGGING
session_start();
require_once __DIR__ . '/../../config/database.php';

$email = 'admin@lawfirm.com';
$password = 'admin123';

echo "<h2>Login Debugging Tool</h2>";

// 1. Check if user exists
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute(['email' => $email]);
$user = $stmt->fetch();

if (!$user) {
    die("❌ User not found with email: " . $email);
}

echo "<h3>✅ User found:</h3>";
echo "<pre>";
echo "ID: " . $user['id'] . "\n";
echo "Name: " . $user['name'] . "\n";
echo "Email: " . $user['email'] . "\n";
echo "Role: " . $user['role'] . "\n";
echo "Password hash: " . $user['password'] . "\n";
echo "Hash length: " . strlen($user['password']) . " characters\n";
echo "</pre>";

// 2. Test password verification
echo "<h3>Password Verification Test:</h3>";
$verify_result = password_verify($password, $user['password']);
echo "Testing password: '{$password}'<br>";
echo "password_verify() result: " . ($verify_result ? "✅ TRUE (matches)" : "❌ FALSE (does not match)") . "<br>";

// 3. If verification fails, try to see what's wrong
if (!$verify_result) {
    echo "<h3 style='color:red'>⚠️ Password verification failed! Possible reasons:</h3>";
    echo "<ul>";
    echo "<li>The hash might be corrupted or have extra spaces</li>";
    echo "<li>The password might be different (check for case sensitivity)</li>";
    echo "<li>The hash algorithm might not be supported</li>";
    echo "</ul>";

    // Try to generate a new hash directly
    echo "<h3>Generating new hash for '{$password}':</h3>";
    $new_hash = password_hash($password, PASSWORD_DEFAULT);
    echo "New hash: <code>{$new_hash}</code><br>";
    echo "New hash length: " . strlen($new_hash) . " characters<br>";

    // Test the new hash
    echo "Testing new hash: " . (password_verify($password, $new_hash) ? "✅ Works" : "❌ Fails") . "<br>";

    echo "<h3>Try updating with this exact SQL:</h3>";
    echo "<code style='background:#f4f4f4;padding:10px;display:block;'>";
    echo "UPDATE users SET password = '{$new_hash}' WHERE email = '{$email}';";
    echo "</code>";
}

// 4. Check session handling
echo "<h3>Session Test:</h3>";
$_SESSION['test'] = 'Session is working';
echo "Session ID: " . session_id() . "<br>";
echo "Session test value: " . ($_SESSION['test'] ?? 'Not set') . "<br>";

// 5. Test database connection details
echo "<h3>Database Info:</h3>";
echo "PDO Connection: " . (isset($pdo) ? "✅ Connected" : "❌ Not connected") . "<br>";
echo "Database name: law_firm_db<br>";
echo "Table: users<br>";

// 6. Show all users in database
echo "<h3>All users in database:</h3>";
$all_users = $pdo->query("SELECT id, name, email, role, LEFT(password, 20) as hash_preview FROM users")->fetchAll();
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Hash Preview</th></tr>";
foreach ($all_users as $u) {
    echo "<tr>";
    echo "<td>{$u['id']}</td>";
    echo "<td>{$u['name']}</td>";
    echo "<td>{$u['email']}</td>";
    echo "<td>{$u['role']}</td>";
    echo "<td>{$u['hash_preview']}...</td>";
    echo "</tr>";
}
echo "</table>";
?>
