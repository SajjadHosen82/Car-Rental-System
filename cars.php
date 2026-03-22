<?php
require_once "includes/db_connect.php";
require_once "includes/header.php";

$cars = mysqli_query($conn, "SELECT * FROM cars");
?>

<h2 class="section-title">Available Cars</h2>

<div class="row justify-content-center">
<?php while($car = mysqli_fetch_assoc($cars)): ?>
  <div class="col-md-4 mb-4">
    <div class="card p-3">

      <img 
        src="/car_rental/assets/images/<?php echo $car['image']; ?>" 
        class="card-img-top"
        alt="<?php echo $car['name']; ?>"
      >

      <div class="card-body">
        <h5><?php echo $car['name']; ?></h5>
        <p><strong>৳<?php echo $car['price_per_day']; ?> / day</strong></p>

        <a href="car_details.php?id=<?php echo $car['id']; ?>" class="btn btn-primary w-100">
          View Details
        </a>
      </div>
    </div>
  </div>
<?php endwhile; ?>
</div>

<?php require_once "includes/footer.php"; ?>
