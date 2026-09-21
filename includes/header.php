<?php
$basePath = $basePath ?? '';
$pageTitle = $pageTitle ?? 'QuizNova';
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="QuizNova interactive quiz and trivia game">
    <title><?= e($pageTitle) ?> | QuizNova</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e($basePath) ?>assets/css/style.css">
</head>
<body>
<div class="space-bg" aria-hidden="true">
    <span class="planet planet-one"></span>
    <span class="planet planet-two"></span>
    <span class="star star-a">✦</span>
    <span class="star star-b">✦</span>
    <span class="star star-c">✦</span>
</div>
<nav class="navbar navbar-expand-lg navbar-dark quiz-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= e($basePath) ?>index.php">
            <span class="brand-orbit">🪐</span>
            <span class="brand-text">Quiz<span>Nova</span></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto gap-lg-2">
                <li class="nav-item"><a class="nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>" href="<?= e($basePath) ?>index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link <?= $currentPage === 'categories.php' ? 'active' : '' ?>" href="<?= e($basePath) ?>categories.php">Categories</a></li>
                <li class="nav-item"><a class="nav-link <?= $currentPage === 'leaderboard.php' ? 'active' : '' ?>" href="<?= e($basePath) ?>leaderboard.php">Leaderboard</a></li>
                <li class="nav-item"><a class="nav-link <?= $currentPage === 'contact.php' ? 'active' : '' ?>" href="<?= e($basePath) ?>contact.php">Contact</a></li>
            </ul>
            <div class="d-flex gap-2 nav-actions">
                <?php if (is_logged_in()): ?>
                    <a class="btn btn-ghost" href="<?= e($basePath) ?>dashboard.php">Dashboard</a>
                    <a class="btn btn-primary-glow" href="<?= e($basePath) ?>auth/logout.php">Logout</a>
                <?php else: ?>
                    <a class="btn btn-ghost" href="<?= e($basePath) ?>auth/register.php">Register</a>
                    <a class="btn btn-primary-glow" href="<?= e($basePath) ?>auth/login.php">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<main>
<?php if ($msg = flash_message()): ?>
<div class="container mt-3"><div class="alert quiz-alert mb-0"><?= e($msg) ?></div></div>
<?php endif; ?>
