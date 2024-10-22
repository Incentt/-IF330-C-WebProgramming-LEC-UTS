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
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="../output.css" rel="stylesheet">
</head>

<body class="bg-dark text-gray-200 flex items-center justify-center min-h-screen">
    <div class="bg-light-10 rounded-lg shadow-md p-6 w-full max-w-md text-center justify-center  flex flex-col">
        <h2 class="text-2xl font-bold mb-4">User Profile</h2>
        <div class="flex justify-center">
            <img src="../uploads/<?= htmlspecialchars(string: $user['profile_image']) ?: 'default-profile.png'; ?>"
                alt="Profile Image" class="w-24 h-24 rounded-full border-2 border-gray-600 object-cover mb-4">

        </div>
        <h3 class="text-xl font-semibold"><?= htmlspecialchars($user['username']); ?></h3>
        <p class="text-gray-400">Email: <?= htmlspecialchars($user['email']); ?></p>
        <p class="text-gray-400">Role: <?= htmlspecialchars($user['role']); ?></p>
        <a href="manage_users.php"
            class="mt-4 inline-block bg-gradient text-white font-semibold py-2 px-4 rounded">Back to
            Manage Users</a>
    </div>
</body>

</html>