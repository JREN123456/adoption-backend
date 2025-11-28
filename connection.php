<?php
// db.php - MySQL connection
// Edit $db_user / $db_pass if your MySQL credentials are different.

$host = 'localhost';
$db_user = 'root';
$db_pass = ''; // set your MySQL password here
$db_name = 'adoption';

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    // In production, don't echo raw error messages.
    die("Database connection failed: " . $conn->connect_error);
}
$conn->set_charset('utf8mb4');
?>
