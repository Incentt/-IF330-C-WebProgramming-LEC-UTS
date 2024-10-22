<?php
require '../db_connection.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$stmt = $pdo->query("SELECT * FROM events");
$events = $stmt->fetchAll();

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
    <link rel="stylesheet" href="../styles.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        img {
            width: 100px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Events</h2>
        <a href="index.php" class="btn">Back to Dashboard</a>
        <a href="create_event.php" class="btn">Create New Event</a>

        <table>
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Image</th> <!-- Kolom untuk gambar -->
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                <tr>
                    <td><?= htmlspecialchars($event['name']); ?></td>
                    <td><?= htmlspecialchars($event['date']); ?></td>
                    <td><?= htmlspecialchars($event['location']); ?></td>
                    <td>
                        <?php if ($event['image']): ?>
                            <img src="../uploads/<?= htmlspecialchars($event['image']); ?>" alt="<?= htmlspecialchars($event['name']); ?>">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit_event.php?id=<?= $event['id']; ?>">Edit</a>
                        <a href="?delete=<?= $event['id']; ?>" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
