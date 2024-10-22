<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    // Logic to update the password in the database
    header("Location: login.php");
    exit();
}
?>

<h2>Reset Password</h2>
<form method="POST" action="">
    <input type="password" name="new_password" placeholder="New Password" required>
    <button type="submit">Reset Password</button>
</form>
