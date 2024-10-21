<?php
include('config.php');
session_start();

if(!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if(isset($_GET['id'])) {
    $event_id = $_GET['id'];
    $query = "DELETE FROM events WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $event_id);

    if($stmt->execute()) {
        echo "Event deleted successfully!";
    } else {
        echo "Failed to delete event.";
    }
}
?>
