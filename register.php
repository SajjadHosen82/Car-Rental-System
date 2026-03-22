<?php
session_start();
require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/includes/functions.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if ($name === '' || $email === '' || $password === '' || $confirm === '') {
        $error = "All fields are required.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $result = registerUser($conn, $name, $email, $password);
        if ($result === true) {
            $success = "Registration successful. <a href='login.php'>Login now</a>";
        } else {
            $error = $result;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="assets/css/register.css">
</head>
<body>

<div class="auth-page">
    <div class="auth-card">
        <h2>Register</h2>
        <p class="sub">Create a new account</p>

        <?php if ($error): ?>
            <div class="auth-error"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="auth-success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="auth-group">
                <input type="text" name="name" placeholder="Full Name" required>
            </div>

            <div class="auth-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <div class="auth-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <div class="auth-group">
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            </div>

            <button type="submit" class="auth-btn">Register</button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="login.php">Login</a>
        </div>
    </div>
</div>

</body>
</html>
