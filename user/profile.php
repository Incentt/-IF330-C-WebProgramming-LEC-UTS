<?php
include('config.php');
session_start();
$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>  

<h2>Your Profile</h2>
<p>Name: <?php echo htmlspecialchars($user['name']); ?></p>
<p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
<a href="edit_profile.php">Edit Profile</a>
