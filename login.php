<?php
session_start();
require "connection.php"; // Assumes this file connects to your database

$email = $_POST["email"];
$password = $_POST["password"];

$stmt = $conn->prepare("SELECT id, first_name, last_name, email, password, role 
                        FROM users 
                        WHERE email=? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo "invalid";
    exit;
}

$stmt->bind_result($id, $first, $last, $emailDB, $hashedPassword, $role);
$stmt->fetch();

// verify password
if (!password_verify($password, $hashedPassword)) {
    echo "invalid";
    exit;
}

// store session on successful login
$_SESSION['user_id'] = $id;
$_SESSION['first_name'] = $first;
$_SESSION['last_name'] = $last;
$_SESSION['email'] = $emailDB;
$_SESSION['role'] = $role;

// send result to JS
if ($role === "admin") {
    echo "admin";
} else {
    // Returns 'user' which triggers the redirect to user.html in index.php
    echo "user"; 
}
?>