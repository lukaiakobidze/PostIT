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
<div class="page_wrapper">
  <main>
    <h1>Admin Panel</h1>
    <h2>Registered Users</h2>
    <table border="1" cellpadding="8" cellspacing="0">
      <tr>
        <th>Email</th>
        <th>Username</th>
        <th>Role</th>
        <th>Actions</th>
      </tr>
      <?php foreach ($users as $user): ?>
        <tr>
          <td><?= htmlspecialchars($user['email']) ?></td>
          <td><?= htmlspecialchars($user['username']) ?></td>
          <td><?= htmlspecialchars($user['role']) ?></td>
          <td class="cell_centered">
            <a href="user_profile.php?username=<?= urlencode($user['username']) ?>">View Profile</a>
          </td>
          
        </tr>
      <?php endforeach; ?>
    </table>
  </main>
</div>
<?php include 'includes/footer.php'; ?>
