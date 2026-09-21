<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    json_response(['success' => false, 'players' => [], 'message' => 'Invalid request method.'], 405);
}
if ($dbError) {
    json_response(['success' => false, 'players' => [], 'message' => $dbError], 500);
}

$sql = "SELECT u.username,
               COUNT(qr.id) AS quizzes,
               COALESCE(SUM(qr.score),0) AS total_score,
               COALESCE(MAX(ROUND((qr.score / NULLIF(qr.total_questions,0)) * 100)),0) AS best_percent
        FROM users u
        JOIN quiz_results qr ON qr.user_id = u.id
        GROUP BY u.id, u.username
        ORDER BY total_score DESC, best_percent DESC, quizzes DESC, u.username ASC
        LIMIT 20";

$result = $conn->query($sql);
if (!$result) {
    json_response(['success' => false, 'players' => [], 'message' => 'Leaderboard tables are not ready. Import database.sql first.'], 500);
}

$players = [];
while ($row = $result->fetch_assoc()) {
    $players[] = $row;
}

json_response(['success' => true, 'players' => $players]);
?>
