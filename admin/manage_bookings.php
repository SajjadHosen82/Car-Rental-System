<?php
session_start();

/* ================= REQUIRED FILES ================= */
require_once '../includes/db_connect.php';
require_once '../includes/auth_check.php';

/* ================= HANDLE APPROVE / CANCEL (PROTECTED) ================= */
if (isset($_GET['action'], $_GET['id'])) {

    $booking_id = (int) $_GET['id'];
    $action = $_GET['action'];

    if ($action === 'approve') {
        $new_status = 'Approved';
    } elseif ($action === 'cancel') {
        $new_status = 'Cancelled';
    } else {
        header("Location: manage_bookings.php");
        exit;
    }

    /*
      CRITICAL RULE:
      Status can be changed ONLY if current status = 'Pending'
    */
    $stmt = $conn->prepare(
        "UPDATE bookings
         SET status = ?
         WHERE id = ?
         AND status = 'Pending'"
    );
    $stmt->bind_param("si", $new_status, $booking_id);
    $stmt->execute();

    header("Location: manage_bookings.php");
    exit;
}

/* ================= FETCH BOOKINGS ================= */
$sql = "
SELECT 
    b.id,
    u.name AS user_name,
    b.phone,
    c.name AS car_name,
    b.pickup_location,
    b.destination,
    b.start_date,
    b.end_date,
    b.pickup_time,
    b.drive_option,
    b.total_price,
    b.status
FROM bookings b
JOIN users u ON b.user_id = u.id
JOIN cars c ON b.car_id = c.id
ORDER BY b.id DESC
";

$result = $conn->query($sql);
?>

<?php include 'admin_header.php'; ?>

<div class="container">
    <h2>Manage Bookings</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Phone</th>
                <th>Car</th>
                <th>Pickup Location</th>
                <th>Destination</th>
                <th>Dates</th>
                <th>Pickup Time</th>
                <th>Drive Option</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= htmlspecialchars($row['user_name']); ?></td>
                    <td><?= htmlspecialchars($row['phone']); ?></td>
                    <td><?= htmlspecialchars($row['car_name']); ?></td>
                    <td><?= htmlspecialchars($row['pickup_location']); ?></td>
                    <td><?= htmlspecialchars($row['destination']); ?></td>
                    <td><?= $row['start_date']; ?> → <?= $row['end_date']; ?></td>
                    <td><?= $row['pickup_time']; ?></td>
                    <td><?= $row['drive_option']; ?></td>
                    <td><?= number_format($row['total_price'], 2); ?></td>

                    <td>
                        <?php if ($row['status'] === 'Pending'): ?>
                            <span style="color:orange;font-weight:bold;">Pending</span>
                        <?php elseif ($row['status'] === 'Approved'): ?>
                            <span style="color:green;font-weight:bold;">Approved</span>
                        <?php else: ?>
                            <span style="color:red;font-weight:bold;">Cancelled</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if ($row['status'] === 'Pending'): ?>
                            <a href="?action=approve&id=<?= $row['id']; ?>"
                               class="btn btn-success btn-sm">Approve</a>

                            <a href="?action=cancel&id=<?= $row['id']; ?>"
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
        <?php else: ?>
            <tr>
                <td colspan="12" style="text-align:center;">No bookings found</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'admin_footer.php'; ?>
