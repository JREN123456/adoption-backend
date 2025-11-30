<?php
session_start();
require "connection.php";

// DENY ACCESS IF NOT ADMIN
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../index.php");
    exit;
}

// Current page
$page = isset($_GET['page']) ? $_GET['page'] : "home";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="admin.css">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
</head>

<body>
<div class="sidebar">
    <h2 class="title">🐾 Admin Panel</h2>
    <ul>
        <li><a href="index.php?page=home"><i class="ri-dashboard-line"></i> Dashboard</a></li>
        <li><a href="index.php?page=users"><i class="ri-user-3-line"></i> Users</a></li>
        <li><a href="index.php?page=pets"><i class="ri-service-line"></i> Pets</a></li>
        <li><a href="index.php?page=adoptions"><i class="ri-file-list-3-line"></i> Adoption Requests</a></li>
        <li><a href="logout.php"><i class="ri-logout-circle-r-line"></i> Logout</a></li>
    </ul>
</div>

<div class="main">
    <div class="header">
        <h1>Welcome, <?= $_SESSION['first_name'] ?> (Admin)</h1>
    </div>

    <div class="content">
    <?php if ($page == "home"): ?>

        <h2>Dashboard Overview</h2>
        <div class="cards">
            <div class="card">
                <h3>Total Users</h3>
                <p>
                <?php
                    $q = $conn->query("SELECT COUNT(*) FROM users");
                    echo $q->fetch_row()[0];
                ?>
                </p>
            </div>

            <div class="card">
                <h3>Total Pets</h3>
                <p>12</p> <!-- replace later when pet table exists -->
            </div>

            <div class="card">
                <h3>Adoption Requests</h3>
                <p>7</p> <!-- same placeholder -->
            </div>
        </div>

    <?php elseif ($page == "users"): ?>

        <h2>User Accounts</h2>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th><th>Name</th><th>Email</th><th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $results = $conn->query("SELECT * FROM users ORDER BY id DESC");
                    while ($row = $results->fetch_assoc()):
                ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['first_name'] . " " . $row['last_name'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['role'] ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    <?php elseif ($page == "pets"): ?>

        <h2>Manage Pets</h2>
        <p>This section will be fully functional when you add pets table. I can generate it on request.</p>

    <?php elseif ($page == "adoptions"): ?>

        <h2>Adoption Requests</h2>
        <p>Waiting for adoption table to be added. I can generate the full adoption system next.</p>

    <?php endif; ?>
    </div>
</div>

</body>
</html>
