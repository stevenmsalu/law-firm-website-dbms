<?php

declare(strict_types=1);

$pdo = require __DIR__ . '/../../database/connection.php';

// This handler only accepts form submissions from the contact page.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

// Server-side check: required fields must not be empty.
if ($name === '' || $email === '' || $subject === '' || $message === '') {
    http_response_code(400);
    echo 'Missing required fields';
    exit;
}

$insertEnquiry = $pdo->prepare(
    'INSERT INTO contacts (name, email, phone, subject, message)
     VALUES (:name, :email, :phone, :subject, :message)'
);

$insertEnquiry->execute([
    'name' => $name,
    'email' => $email,
    'phone' => $phone,
    'subject' => $subject,
    'message' => $message,
]);

echo 'success';
