<?php
include('config.php');
session_start();
$user_id = $_SESSION['user_id'];

$query = "SELECT events.name, events.date, events.location FROM registrations JOIN events ON registrations.event_id = events.id WHERE registrations.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Registered Events</h2>
<table>
    <thead>
        <tr>
            <th>Event Name</th>
            <th>Date</th>
            <th>Location</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['date']); ?></td>
                <td><?php echo htmlspecialchars($row['location']); ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
