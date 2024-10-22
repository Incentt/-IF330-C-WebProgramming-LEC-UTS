<?php
require '../db_connection.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['delete'])) {
    $event_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
    $stmt->execute([$event_id]);
    header("Location: manage_events.php");
    exit();
}
?>
