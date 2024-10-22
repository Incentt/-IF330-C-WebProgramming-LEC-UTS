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

// Assuming you have a default image named 'default-profile.png' in the uploads directory
$defaultProfileImage = '../uploads/default-profile.png'; // Adjust this path as necessary
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="../output.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-dark min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-white">User Dashboard</h2>
            <a href="../logout.php" 
               class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                Logout
            </a>
        </div>

        <!-- Profile Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">User Profile</h3>
            <div class="flex items-center space-x-6">
                <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-gray-200 shadow-lg">
                    <?php if ($user['profile_image']): ?>
                        <img src="../uploads/<?= htmlspecialchars($user['profile_image']); ?>" 
                             alt="Profile Picture"
                             class="w-full h-full object-cover">
                    <?php else: ?>
                        <img src="<?= htmlspecialchars($defaultProfileImage); ?>" 
                             alt="Default Profile Picture"
                             class="w-full h-full object-cover">
                    <?php endif; ?>
                </div>
                <div class="space-y-2">
                    <p class="text-gray-700">Username: <span class="font-medium"><?= htmlspecialchars($user['username']); ?></span></p>
                    <p class="text-gray-700">Email: <span class="font-medium"><?= htmlspecialchars($user['email']); ?></span></p>
                    <a href="edit_profile.php" 
                       class="inline-block px-4 py-2 bg-gradient text-white font-medium rounded-lg transition-colors">
                        Edit Profile
                    </a>
                </div>
            </div>
        </div>

        <!-- Registered Events Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Registered Events</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Event Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Image</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($registrations as $registration): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <a 
                                       onclick="showEventDetails(<?= json_encode($registration); ?>)"
                                       class="text-primary">
                                        
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-gray-700"><?= htmlspecialchars($registration['date']); ?></td>
                                <td class="px-6 py-4 text-gray-700"><?= htmlspecialchars($registration['location']); ?></td>
                                <td class="px-6 py-4">
                                    <?php if ($registration['image']): ?>
                                        <img src="../uploads/<?= htmlspecialchars($registration['image']); ?>"
                                             alt="<?= htmlspecialchars($registration['name']); ?>"
                                             class="h-12 w-12 rounded-lg object-cover">
                                    <?php else: ?>
                                        <span class="text-white">No Image</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <form method="POST" 
                                          action="cancel_registration.php?id=<?= $registration['registration_id']; ?>"
                                          onsubmit="return confirmCancel();">
                                        <button type="submit" 
                                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                                            Cancel
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Available Events Section -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Available Events</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Event Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Image</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($events as $event): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                <?= htmlspecialchars($event['name']); ?>
                                </td>
                                <td class="px-6 py-4 text-gray-700"><?= htmlspecialchars($event['date']); ?></td>
                                <td class="px-6 py-4 text-gray-700"><?= htmlspecialchars($event['location']); ?></td>
                                <td class="px-6 py-4">
                                    <?php if ($event['image']): ?>
                                        <img src="../uploads/<?= htmlspecialchars($event['image']); ?>"
                                             alt="<?= htmlspecialchars($event['name']); ?>"
                                             class="h-12 w-12 rounded-lg object-cover">
                                    <?php else: ?>
                                        <span class="text-white">No Image</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if (in_array($event['id'], $registeredEventIds)): ?>
                                        <button class="px-4 py-2 bg-gray-400 text-white font-medium rounded-lg cursor-not-allowed" 
                                                disabled>
                                            Already Registered
                                        </button>
                                    <?php else: ?>
                                        <a href="register_event.php?id=<?= $event['id']; ?>"
                                           class="inline-block px-4 py-2 bg-primary text-white font-medium rounded-lg transition-colors">
                                            Register
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for Event Details -->
    <div id="eventDetailsModal" 
         class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-semibold text-gray-900" id="eventDetailsTitle"></h4>
                <button onclick="closeModal()" 
                        class="text-gray-400 hover:text-white transition-colors">
                    &times; Close
                </button>
            </div>
            <div id="eventDetails" class="text-gray-700"></div>
        </div>
    </div>

    <script>
        function showEventDetails(event) {
            document.getElementById('eventDetailsTitle').textContent = event.name;
            const details = `
                <div class="space-y-4">
                    <p><span class="font-medium">Date:</span> ${event.date}</p>
                    <p><span class="font-medium">Location:</span> ${event.location}</p>
                    ${event.image ? `
                        <img src="../uploads/${event.image}" 
                             alt="${event.name}" 
                             class="w-full h-48 object-cover rounded-lg">
                    ` : ''}
                </div>
            `;
            document.getElementById('eventDetails').innerHTML = details;
            document.getElementById('eventDetailsModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('eventDetailsModal').classList.add('hidden');
        }

        function confirmCancel() {
            return confirm("Are you sure you want to cancel this registration?");
        }
    </script>
</body>

</html>
