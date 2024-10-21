<?php
session_start();
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $max_participants = $_POST['max_participants'];

    $banner = $_FILES['banner']['name'];
    $banner_tmp = $_FILES['banner']['tmp_name'];
    $banner_folder = 'uploads/' . $banner;

    if (move_uploaded_file($banner_tmp, $banner_folder)) {
        $sql = "INSERT INTO events (name, date, time, location, description, banner, max_participants)
                VALUES ('$name', '$date', '$time', '$location', '$description', '$banner', '$max_participants')";

        if (mysqli_query($conn, $sql)) {
            echo "Event added successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Failed to upload banner.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
</head>
<body>
    <h2>Create Event</h2>
    <form method="POST" action="create_event.php" enctype="multipart/form-data">
        <label for="name">Event Name</label>
        <input type="text" name="name" id="name" required><br><br>

        <label for="date">Date</label>
        <input type="date" name="date" id="date" required><br><br>

        <label for="time">Time</label>
        <input type="time" name="time" id="time" required><br><br>

        <label for="location">Location</label>
        <input type="text" name="location" id="location" required><br><br>

        <label for="description">Description</label>
        <textarea name="description" id="description" required></textarea><br><br>

        <label for="max_participants">Max Participants</label>
        <input type="number" name="max_participants" id="max_participants" required><br><br>

        <label for="banner">Event Banner (Image)</label>
        <input type="file" name="banner" id="banner" accept="image/*" required><br><br>

        <button type="submit">Create Event</button>
    </form>
</body>
</html>
