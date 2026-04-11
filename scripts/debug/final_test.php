<?php
// final_test.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../config/database.php';

$email = 'admin@lawfirm.com';
$password = 'admin123';

echo "<h1>Final Login Test</h1>";

// Get the user from database
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo "<h3>Database Record:</h3>";
echo "Email: " . $user['email'] . "<br>";
echo "Role: " . $user['role'] . "<br>";
echo "Password Hash: " . $user['password'] . "<br>";
echo "Hash Length: " . strlen($user['password']) . "<br>";

echo "<h3>Password Test:</h3>";
echo "Password entered: '" . $password . "'<br>";
echo "Password length: " . strlen($password) . "<br>";

// Test password verification
$verify = password_verify($password, $user['password']);
echo "password_verify result: " . ($verify ? "<span style='color:green;font-weight:bold'>TRUE ✓</span>" : "<span style='color:red;font-weight:bold'>FALSE ✗</span>") . "<br>";

if ($verify) {
    echo "<h2 style='color:green'>✓ SUCCESS! The password works!</h2>";

    // Test session creation
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['name'] = $user['name'];

    echo "<h3>Session created successfully!</h3>";
    echo "Session ID: " . session_id() . "<br>";
    echo "<a href='/law-firm-website-dbms/dashboard/admin/index.php'>Click here to go to Admin Dashboard</a>";
} else {
    echo "<h2 style='color:red'>✗ FAILED! The password hash is not verifying.</h2>";

    // Let's try to see what's wrong with the hash
    echo "<h3>Hash Analysis:</h3>";
    $hash_parts = explode('$', $user['password']);
    echo "Hash structure:<br>";
    echo "<pre>";
    print_r($hash_parts);
    echo "</pre>";

    // Generate a brand new hash for testing
    $new_hash = password_hash($password, PASSWORD_DEFAULT);
    echo "Brand new hash for '{$password}': <code>{$new_hash}</code><br>";

    // Test if the new hash works
    $test_new = password_verify($password, $new_hash);
    echo "New hash verification: " . ($test_new ? "✓ Works" : "✗ Fails") . "<br>";

    if ($test_new) {
        echo "<h3>Update your database with this hash:</h3>";
        echo "<code style='background:#f4f4f4;padding:10px;display:block;'>";
        echo "UPDATE users SET password = '{$new_hash}' WHERE email = '{$email}';";
        echo "</code>";
    }
}

// Check if there are multiple admin users
echo "<h3>All admin users in database:</h3>";
$admins = $pdo->query("SELECT id, name, email, role, LEFT(password, 30) as hash_preview FROM users WHERE role = 'admin'")->fetchAll();
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Hash Preview</th></tr>";
foreach ($admins as $admin) {
    echo "<tr>";
    echo "<td>{$admin['id']}</td>";
    echo "<td>{$admin['name']}</td>";
    echo "<td>{$admin['email']}</td>";
    echo "<td>{$admin['role']}</td>";
    echo "<td>{$admin['hash_preview']}...</td>";
    echo "</tr>";
}
echo "</table>";
?>
