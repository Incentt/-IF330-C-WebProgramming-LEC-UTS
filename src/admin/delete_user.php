<?php
include('config.php');
session_start();

if(!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if(isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);

    if($stmt->execute()) {
        echo "User deleted successfully!";
    } else {
        echo "Failed to delete user.";
    }
}
?>
