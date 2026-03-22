<?php
session_start();
require_once "includes/db_connect.php";
require_once "includes/header.php";

/* ================= AUTH CHECK ================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* ================= CANCEL BOOKING ================= */
if (isset($_GET['cancel'])) {
    $cancel_id = intval($_GET['cancel']);

    // Only Pending booking can be cancelled
    mysqli_query($conn, "
        UPDATE bookings 
        SET status = 'Cancelled'
        WHERE id = $cancel_id
          AND user_id = $user_id
          AND status = 'Pending'
    ");

    header("Location: dashboard.php");
    exit;
}

/* ================= FETCH BOOKINGS ================= */
$result = mysqli_query($conn, "
    SELECT b.*, c.name AS car_name
    FROM bookings b
    JOIN cars c ON b.car_id = c.id
    WHERE b.user_id = $user_id
    ORDER BY b.created_at DESC
");
?>

<div class="container my-5">

    <h2 class="mb-4">My Bookings</h2>

    <?php if (mysqli_num_rows($result) == 0): ?>
        <div class="alert alert-info">No bookings found.</div>
    <?php else: ?>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Car</th>
                <th>Pickup</th>
                <th>Destination</th>
                <th>Dates</th>
                <th>Total</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

        <?php $i = 1; while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= htmlspecialchars($row['car_name']); ?></td>
                <td><?= htmlspecialchars($row['pickup_location']); ?></td>
                <td><?= htmlspecialchars($row['destination']); ?></td>
                <td>
                    <?= $row['start_date']; ?><br>
                    <?= $row['end_date']; ?>
                </td>
                <td>৳<?= number_format($row['total_price']); ?></td>

                <!-- ================= BUG FIXED HERE ================= -->
                <td>
                    <?php
                    if ($row['status'] === 'Pending') {
                        echo '<span class="badge bg-warning text-dark">Pending</span>';
                    } elseif ($row['status'] === 'Approved') {
                        echo '<span class="badge bg-success">Approved</span>';
                    } elseif ($row['status'] === 'Cancelled') {
                        echo '<span class="badge bg-danger">Cancelled</span>';
                    }
                    ?>
                </td>

                <td>
                    <?php if ($row['status'] === 'Pending'): ?>
                        <a href="?cancel=<?= $row['id']; ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Cancel this booking?')">
                           Cancel
                        </a>
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>

        </tbody>
    </table>

    <?php endif; ?>

</div>

<?php require_once "includes/footer.php"; ?>
