<?php
// Compatibility route. Preserve the selected category/difficulty query string.
$query = $_SERVER['QUERY_STRING'] ?? '';
header('Location: quiz.html' . ($query !== '' ? '?' . $query : ''));
exit;
