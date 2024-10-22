<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Logic to send reset password email
        header("Location: reset_password.php");
        exit();
    } else {
        echo "Email not found.";
    }
}
?>

<h2>Forgot Password</h2>
<form method="POST" action="">
    <input type="email" name="email" placeholder="Enter your email" required>
    <button type="submit">Send Reset Link</button>
</form>
