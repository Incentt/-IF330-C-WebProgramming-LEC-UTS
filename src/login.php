<?php
session_start();
include('config.php');

// Redirect if user is already logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT role FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $_SESSION['role'] = $user['role'];
        if ($user['role'] == 'admin') {
            header("Location: admin/admin_dashboard.php");
        } else {
            header("Location: user/user_dashboard.php");
        }
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on the user role
            if ($user['role'] == 'admin') {
                header("Location: admin");
            } else {
                header("Location: user");
            }
            exit();
        }
    }

    // Invalid login
    $_SESSION['error_message'] = "Invalid email or password.";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Check for error message and display it
$errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['error_message']); // Remove the error message from the session
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        id="login-form">
        <h2 class="text-white font-bold text-2xl mb-6">Login</h2>
        <?php if ($errorMessage): ?>
            <div class="error text-danger text-xs mb-2"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <div class="flex flex-col items-start mb-4">
                <label class="text-white text-xs mb-2" for="email">Email</label>
                <input class="bg-dark p-2 text-xs text-white flex h-8 w-full shadow-sm" type="email" name="email"
                    id="email" placeholder="example@gmail.com" required>
            </div>
            <div class="flex flex-col items-start">
                <label class="text-white text-xs mb-2" for="password">Password</label>
                <input placeholder="password" class="bg-dark p-2 text-xs text-white flex h-8 w-full shadow-sm"
                    type="password" name="password" id="password" required><br><br>
            </div>
           
            <button
                class="mb-2 bg-gradient rounded-full text-sm w-full font-bold px-6 py-1 text-white transition duration-400 ease-in-out hover:shadow-glowy"
                type="submit">Login</button>
        </form>
        <h1 class="text-white text-xs">Don't have an account? <a class="font-bold text-secondary"
                href="register.php">Register here</a></h1>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loginForm = document.getElementById('login-form');
            // Add the active class to trigger the slide-in effect
            setTimeout(() => {
                loginForm.classList.add('slide-in-active');
            }, 100); // Delay to allow the DOM to load
        });
    </script>
</body>

</html>
