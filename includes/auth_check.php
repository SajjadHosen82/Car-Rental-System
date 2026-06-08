<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function protectPage() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /car_rental/login.php");
        exit;
    }
}

function protectAdmin() {
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        header("Location: /car_rental/admin/index.php");
        exit;
    }
}
?>

