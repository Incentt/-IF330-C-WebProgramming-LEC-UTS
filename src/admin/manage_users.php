<?php
include('config.php');
session_start();

if(!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$query = "SELECT * FROM users";
$result = $conn->query($query);
?>

<h2>Manage Users</h2>
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><a href="delete_user.php?id=<?php echo $row['id']; ?>">Delete</a></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
