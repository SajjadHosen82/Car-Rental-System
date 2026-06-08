<?php
require_once "../includes/db_connect.php";
require_once "../includes/auth_check.php";
require_once "admin_header.php";

$id = intval($_GET['id']);
$res = mysqli_query($conn, "SELECT * FROM cars WHERE id=$id");
$car = mysqli_fetch_assoc($res);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name  = mysqli_real_escape_string($conn, $_POST['name']);
    $model = mysqli_real_escape_string($conn, $_POST['model']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // image handling
    $newImage = $_FILES['image']['name'];

    if (!empty($newImage)) {
        $tmp = $_FILES['image']['tmp_name'];
        $newImageName = time() . "_" . $newImage;

        // old image delete
        if (!empty($car['image']) && file_exists("../assets/images/" . $car['image'])) {
            unlink("../assets/images/" . $car['image']);
        }

        move_uploaded_file($tmp, "../assets/images/" . $newImageName);

        mysqli_query($conn, "
            UPDATE cars 
            SET name='$name', model='$model', price_per_day='$price',
                description='$description', image='$newImageName'
            WHERE id=$id
        ");
    } else {
        // no image change
        mysqli_query($conn, "
            UPDATE cars 
            SET name='$name', model='$model', price_per_day='$price',
                description='$description'
            WHERE id=$id
        ");
    }

    header("Location: manage_cars.php");
    exit();
}
?>

<div class="container mt-4">
  <div class="card shadow-lg p-4">
    <h3 class="mb-4 text-center">✏️ Edit Car</h3>

    <form method="post" enctype="multipart/form-data">

      <div class="mb-3">
        <label class="form-label">Car Name</label>
        <input type="text" name="name" value="<?= $car['name'] ?>" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Model</label>
        <input type="text" name="model" value="<?= $car['model'] ?>" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Price Per Day</label>
        <input type="number" name="price" value="<?= $car['price_per_day'] ?>" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="4"><?= $car['description'] ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Current Image</label><br>
        <img src="/car_rental/assets/images/<?= $car['image'] ?>" width="180" class="rounded border">
      </div>

      <div class="mb-4">
        <label class="form-label">Change Image (optional)</label>
        <input type="file" name="image" class="form-control">
      </div>

      <button class="btn btn-success w-100 py-2">Update Car</button>

    </form>
  </div>
</div>

<?php require_once "admin_footer.php"; ?>
