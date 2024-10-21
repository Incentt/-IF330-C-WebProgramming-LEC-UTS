<?php
include('config.php');
session_start();
$user_id = $_SESSION['user_id'];

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if(!empty($password)){
        $password = password_hash($password, PASSWORD_BCRYPT);
        $query = "UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $name, $email, $password, $user_id);
    } else {
        $query = "UPDATE users SET name = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $name, $email, $user_id);
    }

    if($stmt->execute()){
        echo "Profile updated successfully!";
    } else {
        echo "Failed to update profile.";
    }
}

$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<h2>Edit Profile</h2>
<form method="post">
    <label>Name</label>
    <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required><br>
    <label>Email</label>
    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>
    <label>Password</label>
    <input type="password" name="password"><br>
    <button type="submit">Save Changes</button>
</form>
