<?php
require '../db_connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    
    // Handle photo upload
    $profile_image = $user['profile_image']; // Default to current image

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_image']['tmp_name'];
        $fileName = $_FILES['profile_image']['name'];
        $fileSize = $_FILES['profile_image']['size'];
        $fileType = $_FILES['profile_image']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Create a new file name and set the destination path
        $newFileName = $_SESSION['user_id'] . '.' . $fileExtension;
        $uploadFileDir = '../uploads/';
        $dest_path = $uploadFileDir . $newFileName;

        // Move the file to the upload directory
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $profile_image = $newFileName; // Update to the new image name
        }
    }

    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, profile_image = ? WHERE id = ?");
    $stmt->execute([$username, $email, $profile_image, $_SESSION['user_id']]);

    header("Location: view_profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container">
        <h2>Edit Profile</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>
            <br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
            <br>
            <label for="profile_image">Profile Image:</label>
            <input type="file" id="profile_image" name="profile_image">
            <br>
            <?php if ($user['profile_image']): ?>
                <p>Current Profile Image:</p>
                <img src="../uploads/<?= htmlspecialchars($user['profile_image']); ?>" alt="Profile Image" style="width:100px; height:auto;">
            <?php endif; ?>
            <br>
            <button type="submit" class="btn">Save Changes</button>
        </form>
        <a href="view_profile.php" class="btn">Cancel</a>
    </div>
</body>
</html>
