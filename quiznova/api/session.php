<?php
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    json_response(['loggedIn' => false, 'message' => 'Invalid request method.'], 405);
}

json_response([
    'loggedIn' => is_logged_in(),
    'username' => $_SESSION['username'] ?? null
]);
?>
