<?php
require '../db_connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $registration_id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM registrations WHERE id = ? AND user_id = ?");
    $stmt->execute([$registration_id, $_SESSION['user_id']]);
    header("Location: index.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>
