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
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container">
        <h2>Edit Event</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="name">Event Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($event['name']); ?>" required>
            <label for="date">Date:</label>
            <input type="date" name="date" value="<?= htmlspecialchars($event['date']); ?>" required>
            <label for="location">Location:</label>
            <input type="text" name="location" value="<?= htmlspecialchars($event['location']); ?>" required>
            <label for="image">Event Image:</label>
            <input type="file" name="image" accept="image/*">
            <button type="submit">Update Event</button>
        </form>
        <a href="manage_events.php" class="btn">Back to Manage Events</a>
    </div>
</body>
</html>
