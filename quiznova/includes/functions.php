<?php
// Shared helpers used by authentication, dashboard and JSON API endpoints.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function is_logged_in(): bool {
    return !empty($_SESSION['user_id']);
}

function require_login(string $loginPath = 'auth/login.php'): void {
    if (!is_logged_in()) {
        header('Location: ' . $loginPath . '?required=1');
        exit;
    }
}

function json_response(array $data, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function allowed_categories(): array {
    return ['General Knowledge', 'Science', 'Technology', 'Sports', 'Movies'];
}

function allowed_difficulties(): array {
    return ['Easy', 'Medium', 'Hard'];
}
