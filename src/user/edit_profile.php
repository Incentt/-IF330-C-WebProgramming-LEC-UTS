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

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="../output.css" rel="stylesheet">
</head>

<body class="bg-dark flex items-center justify-center min-h-screen">
    <div class="bg-white rounded-lg shadow-md p-6 w-[28rem]">
        <h2 class="text-2xl font-bold text-center text-dark mb-6">Edit Profile</h2>

        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label for="username" class="block text-dark">Username:</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']); ?>" 
                    required class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label for="email" class="block text-dark">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" 
                    required class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label for="profile_image" class="block text-gray-700">Profile Image:</label>
                <input type="file" id="profile_image" name="profile_image" 
                    class="block w-full text-gray-700 border border-gray-300 rounded-lg">
            </div>
            <?php if ($user['profile_image']): ?>
                <div>
                    <p class="text-gray-600">Current Profile Image:</p>
                    <img src="../uploads/<?= htmlspecialchars($user['profile_image']); ?>" alt="Profile Image" class="w-24 h-auto rounded-lg">
                </div>
            <?php endif; ?>
            <button type="submit" class="w-full bg-gradient text-white font-bold py-2 rounded-lg transition duration-300">
                Save Changes
            </button>
        </form>

        <a href="index.php" class="block text-center text-danger mt-4">Cancel</a>
    </div>
</body>

</html>
