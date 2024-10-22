<?php
require '../db_connection.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $date = $_POST['date'];
    $location = $_POST['location'];

    // Handle file upload
    $image = $_FILES['image']['name'];
    $target = "../uploads/" . basename($image);

    // Insert event into database
    $stmt = $pdo->prepare("INSERT INTO events (name, date, location, image) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$name, $date, $location, $image])) {
        // Move uploaded file
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            header("Location: manage_events.php");
            exit();
        } else {
            echo "Failed to upload image.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container">
        <h2>Create Event</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <label for="name">Event Name:</label>
            <input type="text" name="name" required>
            <label for="date">Date:</label>
            <input type="date" name="date" required>
            <label for="location">Location:</label>
            <input type="text" name="location" required>
            <label for="image">Event Image:</label>
            <input type="file" name="image" accept="image/*" required>
            <button type="submit">Create Event</button>
        </form>
        <a href="manage_events.php" class="btn">Back to Manage Events</a>
    </div>
</body>
</html>
