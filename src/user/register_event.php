<?php
require '../db_connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$event_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Memeriksa apakah pengguna sudah terdaftar untuk event ini
$stmt = $pdo->prepare("SELECT * FROM registrations WHERE event_id = ? AND user_id = ?");
$stmt->execute([$event_id, $user_id]);
$registration = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mendaftar untuk event
    if (!$registration) {
        $stmt = $pdo->prepare("INSERT INTO registrations (event_id, user_id) VALUES (?, ?)");
        $stmt->execute([$event_id, $user_id]);
        header("Location: index.php");
        exit();
    } else {
        // Menghapus pendaftaran jika pengguna ingin membatalkannya
        $stmt = $pdo->prepare("DELETE FROM registrations WHERE event_id = ? AND user_id = ?");
        $stmt->execute([$event_id, $user_id]);
        header("Location: index.php");
        exit();
    }
}

// Mengambil detail event
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch();

if (!$event) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for Event</title>
    <link rel="stylesheet" href="../styles.css">
    <script>
        function confirmAction() {
            return confirm("Are you sure you want to proceed with this action?");
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Register for Event: <?= htmlspecialchars($event['name']); ?></h2>
        <p>Date: <?= htmlspecialchars($event['date']); ?></p>
        <p>Location: <?= htmlspecialchars($event['location']); ?></p>
        <?php if ($registration): ?>
            <p>You are already registered for this event.</p>
            <form method="POST" onsubmit="return confirmAction();">
                <button type="submit">Cancel Registration</button>
            </form>
        <?php else: ?>
            <form method="POST" onsubmit="return confirmAction();">
                <button type="submit">Register for Event</button>
            </form>
        <?php endif; ?>
        <a href="index.php" class="btn">Back to Dashboard</a>
    </div>
</body>
</html>
