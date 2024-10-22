<?php
require '../db_connection.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();

if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    header("Location: manage_users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="../output.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-dark text-white flex items-center justify-center min-h-screen">
    <div class="flex flex-col justify-center mx-auto p-6 bg-light-10 rounded-lg shadow-md w-full max-w-4xl">
        <h2 class="text-2xl font-bold text-center mb-4 text-white">Manage Users</h2>
        <div class="flex justify-center">
            <a href="index.php" class="mb-4 text-center text-white p-2 rounded-lg bg-gradient ">Back to
                Dashboard</a>
        </div>

        <table class="min-w-full bg-gray-100 border border-gray-300 rounded-lg">
            <thead>
                <tr>
                    <th class="py-2 px-4 text-left text-dark border-b border-gray-300 bg-gray-200">User Name</th>
                    <th class="py-2 px-4 text-left text-dark border-b border-gray-300 bg-gray-200">Email</th>
                    <th class="py-2 px-4 text-left text-dark border-b border-gray-300 bg-gray-200">Role</th>
                    <th class="py-2 px-4 text-center text-dark border-b border-gray-300 bg-gray-200">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr class="hover:bg-gray-200">
                        <td class="py-2 px-4 text-left text-dark border-b border-gray-300">
                            <?= htmlspecialchars($user['username']); ?>
                        </td>
                        <td class="py-2 px-4 text-left text-dark border-b border-gray-300">
                            <?= htmlspecialchars($user['email']); ?>
                        </td>
                        <td class="py-2 px-4 text-left text-dark border-b border-gray-300">
                            <?= htmlspecialchars($user['role']); ?>
                        </td>
                        <td class="py-2 px-4 text-center  text-dark border-b border-gray-300">

                            <div class="flex  justify-between">
                                <div class="bg-primary rounded-lg p-2">
                                    <a href="user_profile.php?id=<?= $user['id']; ?>" class="text-white">View Profile</a>

                                </div>
                                <div class="bg-red-500 w-24 flex justify-center rounded-lg p-2">

                                    <a href="?delete=<?= $user['id']; ?>"
                                        onclick="return confirm('Are you sure you want to delete this user?');"
                                        class="text-white hover:underline">Delete</a>
                                </div>
                            </div>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>