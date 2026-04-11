<?php
// verify_fix.php - Place in project root (law-firm-website-dbms/)
require_once __DIR__ . '/../../config/database.php';

$email = 'admin@lawfirm.com';
$password = 'admin123';

echo "<h2>Password Verification Test</h2>";

$stmt = $pdo->prepare("SELECT password FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    die("❌ User not found: " . $email);
}

echo "Email: " . $email . "<br>";
echo "Hash in database: " . $user['password'] . "<br>";
echo "Hash length: " . strlen($user['password']) . " characters (should be 60)<br>";
echo "Password being tested: " . $password . "<br>";

$verification = password_verify($password, $user['password']);

if ($verification) {
    echo "<h3 style='color:green'>✅ SUCCESS! Password verification worked!</h3>";
    echo "<p>You can now login at: <a href='/law-firm-website-dbms/auth/login.php'>http://localhost/law-firm-website-dbms/auth/login.php</a></p>";
} else {
    echo "<h3 style='color:red'>❌ Still failing. Hash needs to be updated.</h3>";

    // Generate a working hash
    $new_hash = password_hash($password, PASSWORD_DEFAULT);
    echo "<p>Use this SQL to fix:</p>";
    echo "<code style='background:#f4f4f4;padding:10px;display:block;'>";
    echo "UPDATE users SET password = '$new_hash' WHERE email = '$email';";
    echo "</code>";
}
?>
