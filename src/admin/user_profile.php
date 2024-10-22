<?php
require '../db_connection.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_GET['id'] ?? null;

if ($user_id) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
} else {
    header("Location: manage_users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%; /* This makes the image round */
            object-fit: cover; /* This ensures the image covers the entire area */
            border: 2px solid #ddd; /* Optional: adds a border around the image */
        }
        .container {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Profile</h2>
        <img src="../uploads/<?= htmlspecialchars($user['profile_image']); ?>" alt="Profile Image" class="profile-image">
        <h3><?= htmlspecialchars($user['username']); ?></h3>
        <p>Email: <?= htmlspecialchars($user['email']); ?></p>
        <p>Role: <?= htmlspecialchars($user['role']); ?></p>
        <a href="manage_users.php" class="btn">Back to Manage Users</a>
    </div>
</body>
</html>
