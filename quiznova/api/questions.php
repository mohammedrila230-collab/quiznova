<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    json_response(['success' => false, 'message' => 'Invalid request method.'], 405);
}
if ($dbError) {
    json_response(['success' => false, 'message' => $dbError], 500);
}

$category = trim($_GET['category'] ?? '');
$difficulty = trim($_GET['difficulty'] ?? '');

if (!in_array($category, allowed_categories(), true) || !in_array($difficulty, allowed_difficulties(), true)) {
    json_response(['success' => false, 'message' => 'Invalid category or difficulty.'], 422);
}

$stmt = $conn->prepare('SELECT id, question, option_a, option_b, option_c, option_d, correct_answer FROM questions WHERE category = ? AND difficulty = ? ORDER BY RAND() LIMIT 5');
if (!$stmt) {
    json_response(['success' => false, 'message' => 'Question table is not ready. Import database.sql first.'], 500);
}

$stmt->bind_param('ss', $category, $difficulty);
$stmt->execute();
$result = $stmt->get_result();
$questions = [];
while ($row = $result->fetch_assoc()) {
    $questions[] = $row;
}

if (count($questions) < 1) {
    json_response(['success' => false, 'message' => 'No questions were found for this challenge.'], 404);
}

json_response(['success' => true, 'questions' => $questions]);
?>
