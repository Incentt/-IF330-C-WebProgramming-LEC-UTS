<?php
require '../db_connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT events.name, events.date, events.location FROM registrations JOIN events ON registrations.event_id = events.id WHERE registrations.user_id = ?");
$stmt->execute([$user_id]);
$registered_events = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Events</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Registered Events</h2>
        <a href="index.php" class="btn">Back to Dashboard</a>

        <table>
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Location</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registered_events as $event): ?>
                <tr>
                    <td><?= htmlspecialchars($event['name']); ?></td>
                    <td><?= htmlspecialchars($event['date']); ?></td>
                    <td><?= htmlspecialchars($event['location']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
