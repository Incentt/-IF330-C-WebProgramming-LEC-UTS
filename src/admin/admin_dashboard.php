<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <p>Welcome, <?php echo $_SESSION['username']; ?>!</p>
    <p><a href="create_event.php">Create Event</a></p>
    <p><a href="manage_events.php">Manage Events</a></p>
    <p><a href="manage_users.php">Manage Users</a></p>
    <p><a href="../logout.php">Logout</a></p>
</body>
</html>
