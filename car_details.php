<?php
require_once "includes/db_connect.php";
require_once "includes/header.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);
$res = mysqli_query($conn, "SELECT * FROM cars WHERE id=$id");
$car = mysqli_fetch_assoc($res);
?>

<div class="container my-5 car-details">
  <div class="row align-items-start">

    <!-- IMAGE -->
    <div class="col-md-6 mb-4">
      <img 
        src="/car_rental/assets/images/<?php echo $car['image']; ?>" 
        class="img-fluid rounded shadow"
        style="max-height:450px; width:100%; object-fit:contain;"
      >
    </div>

    <!-- DETAILS -->
    <div class="col-md-6">
      <h2 class="mb-3"><?php echo $car['name']; ?></h2>

      <p style="
        white-space: normal;
        word-break: break-word;
        line-height: 1.6;
        font-size: 18px;
      ">
        <?php echo nl2br($car['description']); ?>
      </p>

      <h4 class="my-3">
        ৳<?php echo $car['price_per_day']; ?> <small>/ day</small>
      </h4>

      <a href="book_car.php?id=<?php echo $car['id']; ?>" class="btn btn-success btn-lg">
        Book Now
      </a>
    </div>

  </div>
</div>

<?php require_once "includes/footer.php"; ?>
