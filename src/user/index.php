<?php
require '../db_connection.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

// Ambil data pengguna
$stmt = $pdo->prepare("SELECT username, email, profile_image FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Mengambil data event yang tersedia
$stmt = $pdo->query("SELECT * FROM events");
$events = $stmt->fetchAll();

// Mengambil data registrasi pengguna
$stmt = $pdo->prepare("SELECT e.name, e.date, e.location, e.image, r.id AS registration_id FROM registrations r JOIN events e ON r.event_id = e.id WHERE r.user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$registrations = $stmt->fetchAll();

// Buat array untuk menyimpan event yang sudah terdaftar
$registeredEventIds = array_column($registrations, 'registration_id');
?>
<?php
// Assuming you have a default image named 'default-profile.png' in the uploads directory
$defaultProfileImage = '../uploads/default-profile.png'; // Adjust this path as necessary
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        /* Your existing styles */
    </style>
    <script>
        // Your existing scripts
    </script>
</head>
<body>
    <div class="container">
        <h2>User Dashboard</h2>
        <a href="../logout.php" class="btn">Logout</a>

        <h3>User Profile</h3>
        <?php if ($user['profile_image']): ?>
            <img src="../uploads/<?= htmlspecialchars($user['profile_image']); ?>" alt="Profile Picture" class="profile-image">
        <?php else: ?>
            <img src="<?= htmlspecialchars($defaultProfileImage); ?>" alt="Default Profile Picture" class="profile-image">
        <?php endif; ?>
        <p>Username: <?= htmlspecialchars($user['username']); ?></p>
        <p>Email: <?= htmlspecialchars($user['email']); ?></p>
        <a href="edit_profile.php" class="btn">Edit Profile</a>

        <h3>Registered Events</h3>
        <table>
            <tr>
                <th>Event Name</th>
                <th>Date</th>
                <th>Location</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($registrations as $registration): ?>
            <tr>
                <td>
                    <a href="#" onclick="showEventDetails(<?= json_encode($registration); ?>)"><?= htmlspecialchars($registration['name']); ?></a>
                </td>
                <td><?= htmlspecialchars($registration['date']); ?></td>
                <td><?= htmlspecialchars($registration['location']); ?></td>
                <td>
                    <?php if ($registration['image']): ?>
                        <img src="../uploads/<?= htmlspecialchars($registration['image']); ?>" alt="<?= htmlspecialchars($registration['name']); ?>">
                    <?php else: ?>
                        No Image
                    <?php endif; ?>
                </td>
                <td>
                    <form method="POST" action="cancel_registration.php?id=<?= $registration['registration_id']; ?>" onsubmit="return confirmCancel();">
                        <button type="submit" class="btn">Cancel</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <h3>Available Events</h3>
        <table>
            <tr>
                <th>Event Name</th>
                <th>Date</th>
                <th>Location</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($events as $event): ?>
            <tr>
                <td>
                    <a href="#" onclick="showEventDetails(<?= json_encode($event); ?>)"><?= htmlspecialchars($event['name']); ?></a>
                </td>
                <td><?= htmlspecialchars($event['date']); ?></td>
                <td><?= htmlspecialchars($event['location']); ?></td>
                <td>
                    <?php if ($event['image']): ?>
                        <img src="../uploads/<?= htmlspecialchars($event['image']); ?>" alt="<?= htmlspecialchars($event['name']); ?>">
                    <?php else: ?>
                        No Image
                    <?php endif; ?>
                </td>
                <td>
                    <?php if (in_array($event['id'], $registeredEventIds)): ?>
                        <button class="btn" disabled>Already Registered</button>
                    <?php else: ?>
                        <a href="register_event.php?id=<?= $event['id']; ?>" class="btn">Register</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Modal for Event Details -->
    <div id="eventDetailsModal" style="display:none; position:fixed; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.8); color:white; padding:20px; overflow:auto;">
        <div style="background:white; color:black; padding:20px;">
            <span style="cursor:pointer; float:right;" onclick="closeModal();">&times; Close</span>
            <div id="eventDetails"></div>
        </div>
    </div>
</body>
</html>