<?php
require '../db_connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container">
        <h2>Profile</h2>
        <p>Username: <?= htmlspecialchars($user['username']); ?></p>
        <p>Email: <?= htmlspecialchars($user['email']); ?></p>
        <a href="edit_profile.php" class="btn">Edit Profile</a>
        <a href="index.php" class="btn">Back to Dashboard</a>
    </div>
</body>
</html>
