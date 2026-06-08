<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// BASE URL relative to web root
$BASE_URL = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if ($BASE_URL == DIRECTORY_SEPARATOR) $BASE_URL = '';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Car Rental</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Main CSS -->
  <link href="<?php echo $BASE_URL; ?>/assets/css/style.css" rel="stylesheet">
</head>


<body class="page-gradient">

<nav class="navbar navbar-expand-lg navbar-dark custom-navbar">
  <div class="container">

    <!-- LOGO -->
    <a class="navbar-brand d-flex align-items-center gap-2"
       href="<?php echo $BASE_URL; ?>/index.php">

      <img src="<?php echo $BASE_URL; ?>/assets/images/logo.png"
           alt="Gari Dorkar Logo"
           class="site-logo">

    </a>

    <!-- TOGGLER -->
    <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- MENU -->
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="<?php echo $BASE_URL; ?>/index.php">Home</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="<?php echo $BASE_URL; ?>/cars.php">Cars</a>
        </li>

        <?php if(isset($_SESSION['user_id'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $BASE_URL; ?>/dashboard.php">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $BASE_URL; ?>/logout.php">Logout</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $BASE_URL; ?>/login.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $BASE_URL; ?>/register.php">Register</a>
          </li>
        <?php endif; ?>

        <li class="nav-item">
          <a class="nav-link" href="<?php echo $BASE_URL; ?>/admin/index.php">Admin</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="site-container container mt-4">
