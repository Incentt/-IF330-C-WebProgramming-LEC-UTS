<?php
require '../db_connection.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Memeriksa apakah 'id' ada dalam parameter GET
if (!isset($_GET['id'])) {
    header("Location: manage_events.php"); // Arahkan ke halaman manajemen jika ID tidak ada
    exit();
}

$id = $_GET['id'];

// Mengambil data event berdasarkan ID
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$id]);
$event = $stmt->fetch();

if (!$event) {
    header("Location: manage_events.php"); // Arahkan jika event tidak ditemukan
    exit();
}

// Proses pembaruan event
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $image = $_FILES['image']['name']; // Ambil nama file gambar

    // Update query
    $stmt = $pdo->prepare("UPDATE events SET name = ?, date = ?, location = ?" . ($image ? ", image = ?" : "") . " WHERE id = ?");
    
    // Jika ada gambar baru, tambahkan ke query
    if ($image) {
        $stmt->execute([$name, $date, $location, $image, $id]);
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $image); // Simpan gambar
    } else {
        $stmt->execute([$name, $date, $location, $id]);
    }

    header("Location: manage_events.php?message=Event updated successfully!"); // Tambahkan parameter pesan
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link href="../output.css" rel="stylesheet">
    <style>
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1); /* Inverts the color to white */
        }
    </style>
    <script>
        function showDatePicker() {
            document.getElementById('event-date').showPicker();
        }
    </script>
</head>
<body class="bg-gray-900 text-gray-200 flex items-center justify-center min-h-screen">
    <div class="bg-gray-800 rounded-lg shadow-md p-6 w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4 text-center">Edit Event</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-300">Event Name:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($event['name']); ?>" required class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-200 focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label for="date" class="block text-sm font-medium text-gray-300">Date:</label>
                <input  onclick="showDatePicker()" type="date" id="event-date" name="date" value="<?= htmlspecialchars($event['date']); ?>" required class="block w-full rounded-md border-gray-600 bg-gray-700 text-white focus:border-blue-500 focus:ring-blue-500 cursor-pointer">
            
            
            </div>
            <div class="mb-4">
                <label for="location" class="block text-sm font-medium text-gray-300">Location:</label>
                <input type="text" name="location" value="<?= htmlspecialchars($event['location']); ?>" required class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-200 focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                
                <label for="image" class="block text-sm font-medium text-gray-300">Event Image:</label>
                <input type="file" name="image" accept="image/*" class="mt-1 block w-full text-gray-200">
            </div>
            <button type="submit" class="w-full bg-gradient text-white font-semibold py-2 rounded">Update Event</button>
        </form>
        <a href="manage_events.php" class="mt-4 inline-block text-center w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 rounded">Back to Manage Events</a>
    </div>
</body>
</html>
