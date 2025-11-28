<?php
// signup.php
require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo 'invalid_method';
    exit;
}

// retrieve and basic-validate inputs
$first = trim($_POST['first_name'] ?? '');
$last  = trim($_POST['last_name'] ?? '');
$birthday = trim($_POST['birthday'] ?? '');
$mobile = trim($_POST['mobile'] ?? '');
$address = trim($_POST['address'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$first || !$last || !$birthday || !$mobile || !$address || !$email || !$password) {
    echo 'missing_fields';
    exit;
}

// check for existing email
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->close();
    echo 'email_exists';
    exit;
}
$stmt->close();

// hash password
$hash = password_hash($password, PASSWORD_DEFAULT);

// insert user
$insert = $conn->prepare("INSERT INTO users 
(first_name, last_name, birthday, mobile, address, email, password, role) 
VALUES (?, ?, ?, ?, ?, ?, ?, 'user')");
$insert->bind_param('sssssss', $first, $last, $birthday, $mobile, $address, $email, $hash);

if ($insert->execute()) {
    echo 'success';
} else {
    // for debugging: echo $insert->error;
    echo 'error';
}
$insert->close();
$conn->close();
?>
