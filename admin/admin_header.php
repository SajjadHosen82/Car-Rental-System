<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>CarRental Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">Gari Dorkar Admin</a>

    <div class="d-flex gap-2">
      <a href="dashboard.php" class="btn btn-outline-light btn-sm">Dashboard</a>
      <a href="manage_cars.php" class="btn btn-outline-light btn-sm">Cars</a>
      <a href="manage_users.php" class="btn btn-outline-light btn-sm">Users</a>
      <a href="manage_bookings.php" class="btn btn-outline-light btn-sm">Bookings</a>
      <a href="../logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
