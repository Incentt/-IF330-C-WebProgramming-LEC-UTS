<?php
require '../db_connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$event_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Check if the user is already registered for this event
$stmt = $pdo->prepare("SELECT * FROM registrations WHERE event_id = ? AND user_id = ?");
$stmt->execute([$event_id, $user_id]);
$registration = $stmt->fetch();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$registration) {
        // Register for the event
        $stmt = $pdo->prepare("INSERT INTO registrations (event_id, user_id) VALUES (?, ?)");
        $stmt->execute([$event_id, $user_id]);
        header("Location: index.php");
        exit();
    } else {
        // Unregister from the event
        $stmt = $pdo->prepare("DELETE FROM registrations WHERE event_id = ? AND user_id = ?");
        $stmt->execute([$event_id, $user_id]);
        header("Location: index.php");
        exit();
    }
}

// Fetch event details
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
    <link href="../output.css" rel="stylesheet">
    <script>
        function confirmAction() {
            return confirm("Are you sure you want to proceed with this action?");
        }
    </script>
</head>
<body class="bg-dark flex flex-col items-center justify-center min-h-screen">
    <div class="container mx-auto p-6 rounded-lg bg-white shadow-md w-full max-w-md">
        <h2 class="text-dark font-bold text-2xl mb-4">Register for Event: <?= htmlspecialchars($event['name']); ?></h2>
        <p class="text-dark">Date: <?= htmlspecialchars($event['date']); ?></p>
        <p class="text-white">Location: <?= htmlspecialchars($event['location']); ?></p>
        
        <?php if ($registration): ?>
            <p class="text-secondary mb-4">You are already registered for this event.</p>
            <form method="POST" onsubmit="return confirmAction();">
                <button type="submit" class="w-full bg-danger text-white font-bold py-2 rounded">Cancel Registration</button>
            </form>
        <?php else: ?>
            <form method="POST" onsubmit="return confirmAction();">
                <button type="submit" class="w-full bg-primary  text-white font-bold py-2 rounded">Register for Event</button>
            </form>
        <?php endif; ?>

        <a href="index.php" class="mt-4 inline-block text-danger ">Back to Dashboard</a>
    </div>
</body>
</html>
