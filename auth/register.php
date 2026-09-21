<?php
// The PHP route also serves the HTML registration page on GET, matching the project guide.
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    readfile(__DIR__ . '/register.html');
    exit;
}

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['success' => false, 'message' => 'Invalid request method.'], 405);
}
if ($dbError) {
    json_response(['success' => false, 'message' => $dbError], 500);
}

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if (!preg_match('/^[A-Za-z0-9_]{3,30}$/', $username)) {
    json_response(['success' => false, 'message' => 'Username must be 3–30 characters using letters, numbers or underscore.'], 422);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 120) {
    json_response(['success' => false, 'message' => 'Enter a valid email address.'], 422);
}
if (strlen($password) < 6 || strlen($password) > 72) {
    json_response(['success' => false, 'message' => 'Password must contain 6–72 characters.'], 422);
}
if ($password !== $confirm) {
    json_response(['success' => false, 'message' => 'Passwords do not match.'], 422);
}

$check = $conn->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
if (!$check) {
    json_response(['success' => false, 'message' => 'Users table is not ready. Import database.sql first.'], 500);
}
$check->bind_param('ss', $username, $email);
$check->execute();
if ($check->get_result()->num_rows > 0) {
    json_response(['success' => false, 'message' => 'That username or email is already registered.'], 409);
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
if (!$stmt) {
    json_response(['success' => false, 'message' => 'Account could not be prepared. Import database.sql first.'], 500);
}
$stmt->bind_param('sss', $username, $email, $hash);
if (!$stmt->execute()) {
    json_response(['success' => false, 'message' => 'Account could not be created. Please try again.'], 500);
}

session_regenerate_id(true);
$_SESSION['user_id'] = (int)$stmt->insert_id;
$_SESSION['username'] = $username;
json_response(['success' => true, 'message' => 'Account created successfully.', 'redirect' => '../dashboard.php']);
?>
