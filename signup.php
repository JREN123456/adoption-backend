<?php
require "connection.php";

$first = $_POST["first_name"];
$last = $_POST["last_name"];
$birthday = $_POST["birthday"];
$mobile = $_POST["mobile"];
$address = $_POST["address"];
$email = $_POST["email"];
$password = $_POST["password"];

// check if email already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "email-taken";
    exit;
}

$stmt->close();

$hashed = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (first_name,last_name,birthday,mobile,address,email,password,role)
                        VALUES (?, ?, ?, ?, ?, ?, ?, 'user')");
$stmt->bind_param("sssssss", $first, $last, $birthday, $mobile, $address, $email, $hashed);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "failed";
}

$stmt->close();
$conn->close();
?>
