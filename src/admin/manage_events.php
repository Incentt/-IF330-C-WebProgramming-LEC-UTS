<?php
include('config.php');
session_start();

if(!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$query = "SELECT * FROM events";
$result = $conn->query($query);
?>

<h2>Manage Events</h2>
<table>
    <thead>
        <tr>
            <th>Event Name</th>
            <th>Date</th>
            <th>Location</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['date']); ?></td>
                <td><?php echo htmlspecialchars($row['location']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td>
                    <a href="edit_event.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                    <a href="delete_event.php?id=<?php echo $row['id']; ?>">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
