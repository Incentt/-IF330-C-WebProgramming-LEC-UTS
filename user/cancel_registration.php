<?php
include('config.php');
session_start();
$user_id = $_SESSION['user_id'];

if(isset($_GET['event_id'])){
    $event_id = $_GET['event_id'];

    $query = "DELETE FROM registrations WHERE user_id = ? AND event_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $event_id);
    if($stmt->execute()) {
        echo "Registration canceled successfully!";
    } else {
        echo "Failed to cancel registration.";
    }
}
?>
