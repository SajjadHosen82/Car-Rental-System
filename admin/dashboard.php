<?php
require_once __DIR__ . '/../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* Admin login check */
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

/* Dashboard statistics */
$totalCars = 0;
$totalUsers = 0;
$totalBookings = 0;

if ($conn) {
    $res = $conn->query("SELECT COUNT(*) AS total FROM cars");
    if ($res) $totalCars = $res->fetch_assoc()['total'];

    $res = $conn->query("SELECT COUNT(*) AS total FROM users");
    if ($res) $totalUsers = $res->fetch_assoc()['total'];

    $res = $conn->query("SELECT COUNT(*) AS total FROM bookings");
    if ($res) $totalBookings = $res->fetch_assoc()['total'];
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard | GariDorkar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
  <div class="container">
    <span class="navbar-brand">Gari Dorkar Admin</span>
    <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
  </div>
</nav>

<div class="container mt-5">

  <h2 class="mb-4">Admin Dashboard</h2>

  <div class="row g-4 mb-4">
    <div class="col-md-4">
      <div class="card p-3 text-center">
        <h5>Total Cars</h5>
        <h3><?php echo $totalCars; ?></h3>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card p-3 text-center">
        <h5>Total Users</h5>
        <h3><?php echo $totalUsers; ?></h3>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card p-3 text-center">
        <h5>Total Bookings</h5>
        <h3><?php echo $totalBookings; ?></h3>
      </div>
    </div>
  </div>

  <div class="d-flex gap-3">
    <a href="manage_cars.php" class="btn btn-primary">Manage Cars</a>
    <a href="manage_users.php" class="btn btn-success">Manage Users</a>
    <a href="manage_bookings.php" class="btn btn-warning text-dark">Manage Bookings</a>
  </div>

</div>

<footer class="text-center mt-5 py-3 text-muted">
  © <?php echo date('Y'); ?> CarRental Admin Panel
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
