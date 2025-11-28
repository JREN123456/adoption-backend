<?php
session_start();
require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo 'invalid_method';
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
    echo 'missing_fields';
    exit;
}

$stmt = $conn->prepare("SELECT id, first_name, password, role FROM users WHERE email = ? LIMIT 1");
if (!$stmt) {
    echo 'prepare_error';
    exit;
}

$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $stmt->close();
    echo 'no_user';
    exit;
}

$stmt->bind_result($id, $first_name, $hash, $role);
$stmt->fetch();

if (password_verify($password, $hash)) {

    // set session
    $_SESSION['user_id'] = $id;
    $_SESSION['user_name'] = $first_name;
    $_SESSION['role'] = $role;

    // ⭐ ADMIN REDIRECT
    if ($role === 'admin') {
        header("Location: index.php");
        exit;
    }

    // ⭐ USER REDIRECT
    header("Location: user.php");
    exit;

} else {
    echo 'wrong_password';
}

$stmt->close();
$conn->close();
?>
