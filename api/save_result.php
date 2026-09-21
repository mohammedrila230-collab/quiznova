<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['saved' => false, 'message' => 'Invalid request method.'], 405);
}
if ($dbError) {
    json_response(['saved' => false, 'message' => $dbError], 500);
}
if (!is_logged_in()) {
    json_response(['saved' => false, 'message' => 'Quiz completed. Sign in to save future scores to your dashboard.']);
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    json_response(['saved' => false, 'message' => 'Invalid result data.'], 400);
}

$category = trim($input['category'] ?? '');
$difficulty = trim($input['difficulty'] ?? '');
$score = filter_var($input['score'] ?? null, FILTER_VALIDATE_INT);
$total = filter_var($input['total'] ?? null, FILTER_VALIDATE_INT);

if (!in_array($category, allowed_categories(), true)
    || !in_array($difficulty, allowed_difficulties(), true)
    || $score === false
    || $total === false
    || $score < 0
    || $total < 1
    || $total > 5
    || $score > $total) {
    json_response(['saved' => false, 'message' => 'Invalid quiz result.'], 422);
}

$userId = (int)$_SESSION['user_id'];
$stmt = $conn->prepare('INSERT INTO quiz_results (user_id, category, difficulty, score, total_questions) VALUES (?, ?, ?, ?, ?)');
if (!$stmt) {
    json_response(['saved' => false, 'message' => 'Results table is not ready. Import database.sql first.'], 500);
}

$stmt->bind_param('issii', $userId, $category, $difficulty, $score, $total);
if (!$stmt->execute()) {
    json_response(['saved' => false, 'message' => 'Result could not be saved.'], 500);
}

json_response(['saved' => true, 'message' => 'Score saved successfully to your dashboard.']);
?>
