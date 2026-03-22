<?php
session_start();
require_once 'includes/db_connect.php';
require_once 'includes/auth_check.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$booking_id = (int) $_GET['id'];
$user_id = $_SESSION['user_id'];

/*
RULE:
User can cancel ONLY if status = Pending
Approved booking MUST NOT be cancelled
*/

$sql = "
UPDATE bookings 
SET status = 'Cancelled'
WHERE id = ?
AND user_id = ?
AND status = 'Pending'
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();

header("Location: dashboard.php");
exit;
