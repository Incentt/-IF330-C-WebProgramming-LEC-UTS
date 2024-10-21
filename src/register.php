<?php
session_start();
include('config.php');

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $check_email_sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $check_email_sql);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error_message'] = "Email is already registered. Please use a different email.";
    } else {
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['success_message'] = "Registration successful! You can now <a href='login.php'>Login</a>";
        } else {
            $_SESSION['error_message'] = "Error: " . mysqli_error($conn);
        }
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Check for error or success message and display it
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']); // Remove the error message from the session
}

if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Remove the success message from the session
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="./output.css" rel="stylesheet">
    <style>
        .slide-in {
            transform: translateY(-30px);
            opacity: 0;
            transition: transform 1s ease, opacity 1s ease;
        }

        .slide-in-active {
            transform: translateY(0);
            opacity: 1;
        }
    </style>
</head>

<body class="bg-dark flex flex-col items-center justify-center h-screen">
    <div class="flex rounded-3xl p-6 flex-col justify-between text-center py-8 w-80 bg-light-10 slide-in"
        id="register-form">
        <h2 class="text-white font-bold text-2xl mb-6">Register</h2>

        <?php if ($error_message): ?>
            <div class="text-danger mb-2 text-xs"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="text-secondary mb-2 text-xs"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <div class="flex flex-col items-start mb-4">
                <h1 class="text-white text-xs mb-2" for="username">Username</h1>
                <input class="bg-dark p-2 text-xs text-white flex h-8 w-full shadow-sm" type="text" name="username"
                    id="username" placeholder="username" required>
            </div>
            <div class="flex flex-col items-start mb-4">
                <h1 class="text-white text-xs mb-2" for="email">Email</h1>
                <input placeholder="example@gmail.com" class="bg-dark p-2 text-xs text-white flex h-8 w-full shadow-sm"
                    type="email" name="email" id="email" required>
            </div>
            <div class="flex flex-col items-start mb-8">
                <h1 class="text-white text-xs mb-2" for="password">Password</h1>
                <input placeholder="password" class="bg-dark p-2 text-xs text-white flex h-8 w-full shadow-sm"
                    type="password" name="password" id="password" required>
            </div>

            <button
                class="mb-2 bg-gradient rounded-full text-sm w-full font-bold px-6 py-1 text-white transition duration-400 ease-in-out hover:shadow-glowy"
                type="submit">Register</button>
        </form>
        <h1 class="text-white text-xs">Already have an account? <a class="font-bold text-secondary"
                href="login.php">Login here</a></h1>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const registerForm = document.getElementById('register-form');
            // Add the active class to trigger the slide-in effect
            setTimeout(() => {
                registerForm.classList.add('slide-in-active');
            }, 100); // Delay to allow the DOM to load
        });
    </script>
</body>

</html>