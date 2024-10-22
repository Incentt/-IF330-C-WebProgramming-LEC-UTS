<?php
require '../db_connection.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$stmt = $pdo->query("SELECT * FROM events");
$events = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="../output.css" rel="stylesheet">
</head>

<body class="bg-dark text-gray-200 flex items-center justify-center min-h-screen">
    <div class="container mx-auto p-6 bg-light-10 rounded-lg shadow-md w-full max-w-4xl">
        <h2 class="text-2xl font-bold text-center mb-4 text-white">Admin Dashboard</h2>
        <a href="../logout.php" class="block text-center text-red-500 hover:text-red-700 mb-4">Logout</a>

        <h3 class="text-xl font-semibold mb-2">Event List</h3>
        <table class="min-w-full bg-gray-100 border border-gray-300 rounded-lg">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b border-gray-300 bg-gray-200">Event Name</th>
                    <th class="py-2 px-4 border-b border-gray-300 bg-gray-200">Date</th>
                    <th class="py-2 px-4 border-b border-gray-300 bg-gray-200">Location</th>
                    <th class="py-2 px-4 border-b border-gray-300 bg-gray-200">Image</th>
                    <th class="py-2 px-4 border-b border-gray-300 bg-gray-200">Registrants</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                <tr class="hover:bg-gray-200">
                    <td class="py-2 px-4 border-b border-gray-300"><?= htmlspecialchars($event['name']); ?></td>
                    <td class="py-2 px-4 border-b border-gray-300"><?= htmlspecialchars($event['date']); ?></td>
                    <td class="py-2 px-4 border-b border-gray-300"><?= htmlspecialchars($event['location']); ?></td>
                    <td class="py-2 px-4 border-b border-gray-300">
                        <?php if ($event['image']): ?>
                            <img src="../uploads/<?= htmlspecialchars($event['image']); ?>" alt="<?= htmlspecialchars($event['name']); ?>" class="w-24 h-auto">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    </td>
                    <td class="py-2 px-4 border-b border-gray-300">
                        <?php
                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM registrations WHERE event_id = ?");
                        $stmt->execute([$event['id']]);
                        $registrants = $stmt->fetchColumn();
                        echo htmlspecialchars($registrants);
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="mt-6 flex justify-between">
            <a href="manage_events.php" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-300">Manage Events</a>
            <a href="manage_users.php" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-300">Manage Users</a>
        </div>
    </div>
</body>

</html>
