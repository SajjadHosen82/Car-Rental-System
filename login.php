<?php
session_start();
require_once 'includes/db_connect.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email === '' || $password === '') {
        $error = "All fields are required";
    } else {

        // Prepare statement (MySQLi)
        $stmt = mysqli_prepare($conn, "SELECT id, password FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $user   = mysqli_fetch_assoc($result);

        if (!$user) {
            // Email not found
            $error = "Invalid email or password";
        } else {
            // Password verify
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Invalid email or password";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>

<body class="auth-page">

<div class="auth-card">
    <h2>Sign In</h2>
    <p class="sub">Login to your account</p>

    <?php if ($error): ?>
        <div class="auth-error"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="auth-group">
            <input type="email" name="email" placeholder="Enter Email" required>
        </div>

        <div class="auth-group">
            <input type="password" name="password" placeholder="Enter Password" required>
        </div>

        <button type="submit" class="auth-btn">Login</button>
    </form>

    <div class="auth-footer">
        <a href="register.php">Signup</a>
    </div>
</div>

</body>
</html>
