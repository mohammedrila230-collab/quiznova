<?php
// GET renders the contact page; POST stores a validated message in MySQL.
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    readfile(__DIR__ . '/contact.html');
    exit;
}

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['success' => false, 'message' => 'Invalid request method.'], 405);
}
if ($dbError) {
    json_response(['success' => false, 'message' => $dbError], 500);
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if (strlen($name) < 2 || strlen($name) > 100
    || !filter_var($email, FILTER_VALIDATE_EMAIL)
    || strlen($email) > 120
    || strlen($message) < 5
    || strlen($message) > 2000) {
    json_response(['success' => false, 'message' => 'Enter a valid name, email and message.'], 422);
}

$stmt = $conn->prepare('INSERT INTO messages (name, email, message) VALUES (?, ?, ?)');
if (!$stmt) {
    json_response(['success' => false, 'message' => 'Messages table is not ready. Import database.sql first.'], 500);
}
$stmt->bind_param('sss', $name, $email, $message);
if (!$stmt->execute()) {
    json_response(['success' => false, 'message' => 'Message could not be stored.'], 500);
}

json_response(['success' => true, 'message' => 'Message sent successfully and stored in the database.']);
?>
