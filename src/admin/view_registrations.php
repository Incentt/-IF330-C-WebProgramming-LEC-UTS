<?php
require '../db_connection.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$stmt = $pdo->query("SELECT * FROM registrations");
$registrations = $stmt->fetchAll();
?>

<h2>View Registrations</h2>

<table>
    <tr>
        <th>Username</th>
        <th>Event Name</th>
        <th>Date</th>
    </tr>
    <?php foreach ($registrations as $registration): ?>
    <tr>
        <td><?= $registration['username']; ?></td>
        <td><?= $registration['event_name']; ?></td>
        <td><?= $registration['date']; ?></td>
    </tr>
    <?php endforeach; ?>
</table>
