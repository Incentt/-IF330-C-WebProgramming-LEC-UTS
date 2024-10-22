<?php
require '../db_connection.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Fetch events from the database
$stmt = $pdo->query("SELECT * FROM events");
$events = $stmt->fetchAll();

// Handle event deletion
if (isset($_GET['delete'])) {
    $event_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
    $stmt->execute([$event_id]);
    header("Location: manage_events.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="../output.css" rel="stylesheet">
</head>

<body class="bg-dark text-gray-200 flex items-center justify-center min-h-screen">
    <div class="flex flex-col justify-center mx-auto p-6 bg-light-10 rounded-lg shadow-md w-full max-w-4xl">
        <h2 class="text-2xl font-bold text-center mb-4 text-white">Manage Events</h2>
        <div class="flex justify-between mb-4">
            <a href="index.php" class="text-center text-white p-2 rounded-lg bg-gradient">Back to Dashboard</a>
            <a href="create_event.php" class="text-center text-white p-2 rounded-lg  bg-gradient">Create New Event</a>
        </div>

        <table class="min-w-full bg-gray-100 border border-gray-300 rounded-lg">
            <thead>
                <tr>
                    <th class="py-2 px-4 text-left text-dark border-b border-gray-300 bg-gray-200">Event Name</th>
                    <th class="py-2 px-4 text-left text-dark border-b border-gray-300 bg-gray-200">Date</th>
                    <th class="py-2 px-4 text-left text-dark border-b border-gray-300 bg-gray-200">Location</th>
                    <th class="py-2 px-4 text-left text-dark border-b border-gray-300 bg-gray-200">Image</th>
                    <th class="py-2 px-4 text-center text-dark border-b border-gray-300 bg-gray-200">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                    <tr class="hover:bg-gray-200">
                        <td class="py-2 px-4 text-dark border-b border-gray-300"><?= htmlspecialchars($event['name']); ?>
                        </td>
                        <td class="py-2 px-4 text-dark border-b border-gray-300"><?= htmlspecialchars($event['date']); ?>
                        </td>
                        <td class="py-2 px-4 text-dark border-b border-gray-300">
                            <?= htmlspecialchars($event['location']); ?>
                        </td>
                        <td class="py-2 px-4 text-dark border-b border-gray-300">
                            <?php if ($event['image']): ?>
                                <img src="../uploads/<?= htmlspecialchars($event['image']); ?>"
                                    alt="<?= htmlspecialchars($event['name']); ?>" class="h-48 w-auto">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </td>
                        <td class="py-2 px-4 h-full gap-4 text-dark border-b border-gray-300">
                            <div class="flex justify-between gap-4">
                                <div class="bg-primary flex justify-center w-[80%] p-2 rounded-lg">
                                    <a href="edit_event.php?id=<?= $event['id']; ?>" class="text-white text-center">Edit</a>
                                </div>
                                <div class="bg-red-500 p-2 rounded-lg flex w-[80%] justify-center">
                                    <a href="?delete=<?= $event['id']; ?>"
                                        onclick="return confirm('Are you sure you want to delete this event?');"
                                        class="text-white text-center">Delete</a>
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