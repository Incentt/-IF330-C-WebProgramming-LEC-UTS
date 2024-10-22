<?php
session_start();
require 'db_connection.php';

// Jika pengguna belum login, arahkan ke halaman login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
} else {
    // Fetch user role from the database
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Check if the user exists and set the session role
    if ($user) {
        $_SESSION['role'] = $user['role'];
        
        // Redirect based on the user role
        if ($user['role'] == 'admin') {
            header("Location: admin");
        } else {
            header("Location: user");
        }
        exit(); // Make sure to exit after redirect
    } else {
        // If the user is not found, log them out
        session_destroy();
        header("Location: login.php");
        exit();
    }
}
?>
