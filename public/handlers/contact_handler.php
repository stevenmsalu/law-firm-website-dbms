<?php
declare(strict_types=1);

require __DIR__ . '/../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $subject === '' || $message === '') {
    http_response_code(400);
    echo 'Missing required fields';
    exit;
}

$stmt = $pdo->prepare(
    "INSERT INTO contacts (name, email, phone, subject, message)
     VALUES (:name, :email, :phone, :subject, :message)"
);

$stmt->execute([
    'name' => $name,
    'email' => $email,
    'phone' => $phone,
    'subject' => $subject,
    'message' => $message,
]);

echo 'success';