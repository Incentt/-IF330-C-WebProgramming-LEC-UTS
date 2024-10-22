<?php
require '../db_connection.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $date = $_POST['date'];
    $location = $_POST['location'];

    // Handle file upload
    $image = $_FILES['image']['name'];
    $target = "../uploads/" . basename($image);

    // Insert event into the database
    $stmt = $pdo->prepare("INSERT INTO events (name, date, location, image) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$name, $date, $location, $image])) {
        // Move uploaded file
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            header("Location: manage_events.php");
            exit();
        } else {
            $error = "Failed to upload image.";
        }
    } else {
        $error = "Failed to create event.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link href="../output.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-dark w-3xl text-gray-200 flex items-center justify-center min-h-screen">
    <div class="flex flex-col justify-center mx-auto p-6 bg-light-10 rounded-lg shadow-md w-full max-w-lg">
        <h2 class="text-2xl font-bold text-center mb-4 text-white">Create Event</h2>
        
        <?php if (isset($error)): ?>
            <div class="mb-4 text-red-500"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data" class="space-y-4">
            <label for="name" class="block text-white">Event Name:</label>
            <input type="text" placeholder="Masukan nama" name="name" required class="w-full p-2 border text-white bg-dark border-gray-300 rounded">

            <label for="date" class="block text-white">Date:</label>
            <input type="date" name="date" required class="w-full p-2 border text-white bg-dark border-gray-300 rounded">

            <label for="location" class="block text-white">Location:</label>
            <input type="text" name="location" placeholder="Masukan lokasi" required class="w-full p-2 border text-white bg-dark border-gray-300 rounded">

            <label for="image" class="block text-white">Event Image:</label>
            <input type="file" name="image" accept="image/*" required class="w-full border text-white bg-dark border-gray-300 rounded">

            <button type="submit" class="w-full p-2 bg-gradient text-white rounded">Create Event</button>
        </form>
        
        <a href="manage_events.php" class="mt-4 text-center text-white p-2 rounded-lg bg-gray-500 hover:bg-gray-700">Back to Manage Events</a>
    </div>
</body>
</html>
