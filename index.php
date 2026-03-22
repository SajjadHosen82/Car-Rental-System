<?php
require_once "includes/db_connect.php";
require_once "includes/header.php";

/* ========= short description function ========= */
function shortText($text, $limit = 90) {
    return strlen($text) > $limit
        ? substr($text, 0, $limit) . '...'
        : $text;
}

/* ========= get cars ========= */
$result = mysqli_query($conn, "SELECT * FROM cars ORDER BY id DESC");
?>

<div class="hero-heading text-center mb-4">
  <h1 class="hero-title">
    Drive Distinction – Book Today
  </h1>
  <div class="hero-line"></div>
</div>

  <h2 class="section-title">Available Cars for Rent</h2>

  <div class="row justify-content-center">

    <?php while ($car = mysqli_fetch_assoc($result)): ?>
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100 shadow-sm border-0 rounded-4">

          <!-- IMAGE -->
          <div style="height:220px; overflow:hidden;">
            <img
              src="/car_rental/assets/images/<?php echo $car['image']; ?>"
              alt="<?php echo $car['name']; ?>"
              style="width:100%; height:100%; object-fit:cover;"
            >
          </div>

          <!-- BODY -->
          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?php echo $car['name']; ?></h5>

            <p class="text-muted mb-1">
              <strong>Model:</strong> <?php echo $car['model']; ?>
            </p>

            <p class="text-secondary flex-grow-1" style="font-size:14px;">
              <?php echo shortText($car['description']); ?>
            </p>

            <h6 class="mt-2 mb-3">
              ৳<?php echo number_format($car['price_per_day'], 2); ?> <small>/ day</small>
            </h6>

            <a
              href="car_details.php?id=<?php echo $car['id']; ?>"
              class="btn btn-primary w-100 mt-auto"
            >
              View Details
            </a>
          </div>

        </div>
      </div>
    <?php endwhile; ?>

  </div>
</div>

<?php require_once "includes/footer.php"; ?>
