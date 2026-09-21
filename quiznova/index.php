<?php
// Compatibility entry point for servers that prioritize index.php.
// The actual frontend home page is index.html.
header('Location: index.html');
exit;
