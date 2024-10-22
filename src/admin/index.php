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
    <link rel="stylesheet" href="../styles.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
        <h2>Admin Dashboard</h2>
        <a href="../logout.php" class="btn">Logout</a>

        <h3>Event List</h3>
        <table>
            <tr>
                <th>Event Name</th>
                <th>Date</th>
                <th>Location</th>
                <th>Image</th> <!-- Kolom untuk gambar -->
                <th>Registrants</th>
            </tr>
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
                    <?php
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM registrations WHERE event_id = ?");
                    $stmt->execute([$event['id']]);
                    $registrants = $stmt->fetchColumn();
                    echo htmlspecialchars($registrants);
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <a href="manage_events.php" class="btn">Manage Events</a>
        <a href="manage_users.php" class="btn">Manage Users</a>
    </div>
</body>
</html>
