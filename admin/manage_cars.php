<?php
require_once "../includes/db_connect.php";
require_once "../includes/auth_check.php";
require_once "admin_header.php";

/* ================= DELETE CAR ================= */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // image name আনো
    $res = mysqli_query($conn, "SELECT image FROM cars WHERE id=$id");
    $car = mysqli_fetch_assoc($res);

    if ($car && file_exists("../assets/images/" . $car['image'])) {
        unlink("../assets/images/" . $car['image']); // image delete
    }

    mysqli_query($conn, "DELETE FROM cars WHERE id=$id");

    header("Location: manage_cars.php");
    exit();
}

/* ================= ADD CAR ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name  = mysqli_real_escape_string($conn, $_POST['name']);
    $model = mysqli_real_escape_string($conn, $_POST['model']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $imageName = $_FILES['image']['name'];
    $tmpName   = $_FILES['image']['tmp_name'];

    if (!empty($imageName)) {
        $newImageName = time() . "_" . $imageName;

        move_uploaded_file(
            $tmpName,
            "../assets/images/" . $newImageName
        );

        mysqli_query($conn, "
            INSERT INTO cars (name, model, price_per_day, description, image)
            VALUES ('$name','$model','$price','$description','$newImageName')
        ");
    }
}

/* ================= GET CARS ================= */
$cars = mysqli_query($conn, "SELECT * FROM cars ORDER BY id DESC");
?>

<div class="container mt-4">

<h2 class="mb-4">Manage Cars</h2>

<!-- ================= ADD CAR FORM ================= -->
<form method="post" enctype="multipart/form-data" class="card p-4 mb-4">
  <h4>Add New Car</h4>

  <input type="text" name="name" class="form-control mb-2" placeholder="Car Name" required>
  <input type="text" name="model" class="form-control mb-2" placeholder="Model" required>
  <input type="number" name="price" class="form-control mb-2" placeholder="Price Per Day" required>
  <textarea name="description" class="form-control mb-2" placeholder="Description"></textarea>
  <input type="file" name="image" class="form-control mb-3" required>

  <button class="btn btn-primary">Add Car</button>
</form>

<!-- ================= CAR LIST ================= -->
<h4>All Cars</h4>

<table class="table table-bordered table-striped">
<tr>
  <th>ID</th>
  <th>Name</th>
  <th>Model</th>
  <th>Price</th>
  <th>Image</th>
  <th width="150">Action</th>
</tr>

<?php while($car = mysqli_fetch_assoc($cars)): ?>
<tr>
  <td><?= $car['id'] ?></td>
  <td><?= $car['name'] ?></td>
  <td><?= $car['model'] ?></td>
  <td>৳<?= $car['price_per_day'] ?></td>
  <td>
    <img src="/car_rental/assets/images/<?= $car['image'] ?>" width="90">
  </td>
  <td>
    <a href="edit_car.php?id=<?= $car['id'] ?>" class="btn btn-sm btn-warning">
      Edit
    </a>
    <a 
      href="manage_cars.php?delete=<?= $car['id'] ?>" 
      class="btn btn-sm btn-danger"
      onclick="return confirm('Are you sure?')"
    >
      Delete
    </a>
  </td>
</tr>
<?php endwhile; ?>
</table>

</div>

<?php require_once "admin_footer.php"; ?>
