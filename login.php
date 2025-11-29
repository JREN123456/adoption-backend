<?php
session_start();
require "connection.php";

$email = $_POST["email"];
$password = $_POST["password"];

$stmt = $conn->prepare("SELECT id, first_name, last_name, email, password, role FROM users WHERE email=? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo "invalid";
    exit;
}

$stmt->bind_result($id, $first, $last, $emailDB, $hash, $role);
$stmt->fetch();

if (!password_verify($password, $hash)) {
    echo "invalid";
    exit;
}

// SUCCESS → create session
$_SESSION['user_id'] = $id;
$_SESSION['first_name'] = $first;
$_SESSION['last_name'] = $last;
$_SESSION['email'] = $emailDB;
$_SESSION['role'] = $role;

// output expected result
if ($role === "admin") {
    echo "admin";
} else {
    echo "user";
}
?>
