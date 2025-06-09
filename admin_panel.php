<?php
session_start();
include 'includes/functions.php';

// Only allow admins
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Load all users
$users = [];
foreach (glob('data/users/*.json') as $file) {
    $data = json_decode(file_get_contents($file), true);
    $users[] = $data;
}

include 'includes/header.php';
?>

<main>
  <h1>Admin Panel</h1>
  <a href="index.php">Back to Home</a>
  <h2>Registered Users</h2>
  <table border="1" cellpadding="8" cellspacing="0">
    <tr>
      <th>Email</th>
      <th>Username</th>
      <th>Role</th>
    </tr>
    <?php foreach ($users as $user): ?>
      <tr>
        <td><?= htmlspecialchars($user['email']) ?></td>
        <td><?= htmlspecialchars($user['username']) ?></td>
        <td><?= htmlspecialchars($user['role']) ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
</main>

<?php include 'includes/footer.php'; ?>
