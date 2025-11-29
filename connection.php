<?php
$host = "localhost";
$user = "root";      // your XAMPP user
$pass = "";          // your XAMPP password (often empty)
$db   = "adoption";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
