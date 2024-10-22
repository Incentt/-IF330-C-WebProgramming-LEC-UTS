<?php
require '../db_connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $event = $stmt->fetch();
}
?>

<h2><?= $event['name']; ?></h2>
<p>Date: <?= $event['date']; ?></p>
<p>Location: <?= $event['location']; ?></p>
<form method="POST" action="register_event.php?id=<?= $event['id']; ?>">
    <button type="submit">Register for Event</button>
</form>
