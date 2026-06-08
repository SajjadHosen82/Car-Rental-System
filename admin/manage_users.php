<?php
require_once __DIR__ . '/../includes/functions.php';
include __DIR__ . '/admin_header.php';

// Delete user
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM users WHERE id=$id AND role!='admin'");
}

$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<h2 class="mb-4">Manage Users</h2>

<table class="table table-bordered">
  <tr>
    <th>#</th><th>Name</th><th>Email</th><th>Role</th><th>Action</th>
  </tr>

  <?php while ($u = $users->fetch_assoc()): ?>
  <tr>
    <td><?= $u['id'] ?></td>
    <td><?= htmlspecialchars($u['name']) ?></td>
    <td><?= htmlspecialchars($u['email']) ?></td>
    <td><?= $u['role'] ?></td>
    <td>
      <?php if ($u['role'] !== 'admin'): ?>
        <a href="?delete=<?= $u['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
      <?php else: ?>
        —
      <?php endif; ?>
    </td>
  </tr>
  <?php endwhile; ?>
</table>

<?php include __DIR__ . '/admin_footer.php'; ?>
