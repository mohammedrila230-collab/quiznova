</main>
<footer class="site-footer mt-5">
    <div class="container py-4">
        <div class="row align-items-center g-4">
            <div class="col-md-4">
                <div class="d-flex align-items-center gap-2 mb-1"><span class="brand-orbit">🪐</span><span class="brand-text">Quiz<span>Nova</span></span></div>
                <small>Play • Learn • Grow</small>
            </div>
            <div class="col-md-4 text-md-center footer-links">
                <a href="<?= e($basePath) ?>index.php">Home</a>
                <a href="<?= e($basePath) ?>categories.php">Categories</a>
                <a href="<?= e($basePath) ?>leaderboard.php">Leaderboard</a>
                <a href="<?= e($basePath) ?>contact.php">Contact</a>
            </div>
            <div class="col-md-4 text-md-end"><small>Keep questioning. Keep growing ✦</small></div>
        </div>
        <div class="footer-bottom text-center mt-4 pt-3">© <?= date('Y') ?> QuizNova. Built for curious minds.</div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= e($basePath) ?>assets/js/app.js"></script>
<?php if (!empty($pageScript)): ?>
<script src="<?= e($basePath . $pageScript) ?>"></script>
<?php endif; ?>
</body>
</html>
