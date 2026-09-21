<?php
// The PHP route also serves the HTML login page on GET, matching the project guide.
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    readfile(__DIR__ . '/login.html');
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

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    json_response(['success' => false, 'message' => 'Enter a valid email address and password.'], 422);
}

$stmt = $conn->prepare('SELECT id, username, password FROM users WHERE email = ? LIMIT 1');
if (!$stmt) {
    json_response(['success' => false, 'message' => 'Users table is not ready. Import database.sql first.'], 500);
}
$stmt->bind_param('s', $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user || !password_verify($password, $user['password'])) {
    json_response(['success' => false, 'message' => 'Incorrect email or password.'], 401);
}

session_regenerate_id(true);
$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['username'] = $user['username'];
json_response(['success' => true, 'message' => 'Login successful.', 'redirect' => '../dashboard.php']);
?>
