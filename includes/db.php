<?php
$host = 'localhost';
$dbname = 'quiz_nova';
$dbuser = 'root';
$dbpass = '';

$conn = null;
$dbError = null;

if (!class_exists('mysqli')) {
    $dbError = 'MySQLi extension is not enabled. Enable MySQLi in PHP/WAMP and restart the server.';
} else {
    if (function_exists('mysqli_report')) {
        mysqli_report(MYSQLI_REPORT_OFF);
    }
    $conn = @new mysqli($host, $dbuser, $dbpass, $dbname);
    if ($conn->connect_errno) {
        $dbError = 'Database connection failed. Import database.sql and verify includes/db.php.';
    } else {
        $conn->set_charset('utf8mb4');
    }
}
?>
