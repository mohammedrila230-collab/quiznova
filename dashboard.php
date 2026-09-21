<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_login('auth/login.php');

$userId = (int)$_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'Player';
$totalQuizzes = 0;
$totalScore = 0;
$bestPercent = 0;
$avgPercent = 0;
$results = [];
$breakdown = [];

// Dashboard statistics come entirely from quiz_results for the logged-in user.
if (!$dbError) {
    $summary = $conn->prepare('SELECT COUNT(*) AS total_quizzes,
                                      COALESCE(SUM(score),0) AS total_score,
                                      COALESCE(MAX(ROUND((score / NULLIF(total_questions,0))*100)),0) AS best_percent,
                                      COALESCE(ROUND(AVG((score / NULLIF(total_questions,0))*100)),0) AS avg_percent
                               FROM quiz_results WHERE user_id = ?');
    if (!$summary) {
        $dbError = 'Quiz result tables are not ready. Import database.sql first.';
    } else {
        $summary->bind_param('i', $userId);
        $summary->execute();
        $stats = $summary->get_result()->fetch_assoc();
        $totalQuizzes = (int)($stats['total_quizzes'] ?? 0);
        $totalScore = (int)($stats['total_score'] ?? 0);
        $bestPercent = (int)($stats['best_percent'] ?? 0);
        $avgPercent = (int)($stats['avg_percent'] ?? 0);
    }
}

if (!$dbError) {
    $recent = $conn->prepare('SELECT category, difficulty, score, total_questions, played_at FROM quiz_results WHERE user_id = ? ORDER BY played_at DESC LIMIT 12');
    if (!$recent) {
        $dbError = 'Quiz history could not be loaded. Import database.sql first.';
    } else {
        $recent->bind_param('i', $userId);
        $recent->execute();
        $res = $recent->get_result();
        while ($row = $res->fetch_assoc()) {
            $results[] = $row;
        }
    }
}

if (!$dbError) {
    $cat = $conn->prepare('SELECT category, COUNT(*) AS attempts, ROUND(AVG((score / NULLIF(total_questions,0))*100)) AS accuracy FROM quiz_results WHERE user_id = ? GROUP BY category ORDER BY category');
    if (!$cat) {
        $dbError = 'Category performance could not be loaded. Import database.sql first.';
    } else {
        $cat->bind_param('i', $userId);
        $cat->execute();
        $catRes = $cat->get_result();
        while ($row = $catRes->fetch_assoc()) {
            $breakdown[] = $row;
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="theme-color" content="#06101d">
  <meta name="description" content="QuizNova player dashboard">
  <title>Dashboard | QuizNova</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="ambient" aria-hidden="true"><span class="ambient-orb orb-one"></span><span class="ambient-orb orb-two"></span></div>
<nav class="navbar navbar-dark site-nav"><div class="container-xl"><a class="navbar-brand brand" href="index.html"><span class="brand-mark"></span><span class="brand-word">Quiz<span>Nova</span></span></a><div class="d-flex gap-2"><a class="btn btn-surface" href="categories.html">New quiz</a><a class="btn btn-accent" href="auth/logout.php">Sign out</a></div></div></nav>

<main class="dashboard-main">
  <div class="container-xl">
    <div class="dashboard-heading">
      <div><div class="kicker"><span></span> Player dashboard</div><h1>Welcome, <?= e($username) ?>.</h1><p>Your personal performance overview is calculated from quiz results stored in MySQL.</p></div>
      <a class="btn btn-surface" href="leaderboard.html">View leaderboard <i class="bi bi-arrow-up-right"></i></a>
    </div>

    <?php if ($dbError): ?><div class="alert alert-danger"><?= e($dbError) ?></div><?php endif; ?>

    <div class="metric-grid">
      <article class="premium-card metric-card"><div><small>Quizzes played</small><strong><?= $totalQuizzes ?></strong></div><span><i class="bi bi-collection-play"></i></span></article>
      <article class="premium-card metric-card"><div><small>Total score</small><strong><?= $totalScore ?></strong></div><span><i class="bi bi-activity"></i></span></article>
      <article class="premium-card metric-card"><div><small>Best result</small><strong><?= $bestPercent ?>%</strong></div><span><i class="bi bi-graph-up-arrow"></i></span></article>
      <article class="premium-card metric-card"><div><small>Average accuracy</small><strong><?= $avgPercent ?>%</strong></div><span><i class="bi bi-bullseye"></i></span></article>
    </div>

    <div class="row g-4 mb-4">
      <div class="col-lg-8">
        <section class="premium-card dashboard-table h-100">
          <div class="table-headline"><div><span class="micro-label">Recent activity</span><h2>Quiz history</h2></div><a class="inline-link" href="categories.html">Start another <i class="bi bi-arrow-right"></i></a></div>
          <div class="table-responsive"><table class="table premium-table align-middle mb-0"><thead><tr><th>Category</th><th>Difficulty</th><th>Score</th><th>Result</th><th>Date</th></tr></thead><tbody>
          <?php if (!$results): ?>
            <tr><td colspan="5" class="text-center py-5 muted-text">No quiz history yet. Start a challenge to create your first saved result.</td></tr>
          <?php else: foreach ($results as $row): $p = $row['total_questions'] > 0 ? (int)round(($row['score'] / $row['total_questions']) * 100) : 0; ?>
            <tr><td><strong><?= e($row['category']) ?></strong></td><td><span class="difficulty-tag"><?= e($row['difficulty']) ?></span></td><td><?= (int)$row['score'] ?>/<?= (int)$row['total_questions'] ?></td><td><span class="result-tag"><?= $p ?>%</span></td><td class="muted-text"><?= e(date('Y-m-d H:i', strtotime($row['played_at']))) ?></td></tr>
          <?php endforeach; endif; ?>
          </tbody></table></div>
        </section>
      </div>
      <div class="col-lg-4">
        <aside class="premium-card dashboard-side h-100"><span class="micro-label">Recommended flow</span><h2>Keep improving one level at a time.</h2><p class="section-subtitle">Choose a category, raise the difficulty and compare your next result with your current best.</p><div class="side-steps"><div><span>01</span><p><strong>Select a category</strong><small>Pick one of five topics</small></p></div><div><span>02</span><p><strong>Choose a level</strong><small>Easy, Medium or Hard</small></p></div><div><span>03</span><p><strong>Save the result</strong><small>Scores store automatically</small></p></div></div><a class="btn btn-accent w-100 mt-4" href="categories.html">Choose challenge <i class="bi bi-arrow-right"></i></a></aside>
      </div>
    </div>

    <section class="premium-card breakdown-card">
      <span class="micro-label">Category performance</span><h2>Average accuracy by category</h2>
      <?php if (!$breakdown): ?>
        <p class="muted-text mb-0">Category performance will appear after you complete saved quizzes.</p>
      <?php else: ?>
        <div class="breakdown-list">
          <?php foreach ($breakdown as $row): $accuracy = max(0, min(100, (int)$row['accuracy'])); ?>
            <div class="breakdown-row"><label><?= e($row['category']) ?></label><div class="bar"><span style="width:<?= $accuracy ?>%"></span></div><b><?= $accuracy ?>%</b></div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>
  </div>
</main>
</body>
</html>
