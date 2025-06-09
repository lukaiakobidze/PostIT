<?php
session_start();
include 'includes/functions.php';
include 'includes/user.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = User::login($email, $password);
    if ($user) {
        $_SESSION['user'] = [
            'email' => $user->email,
            'username' => $user->username,
            'role' => $user->role
        ];
        header('Location: index.php');
        exit();
    } else {
        $error = "Invalid credentials.";
    }
}
?>

<form method="POST">
  <h2>Login</h2>
  <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
  <label>Email: <input type="email" name="email" required></label><br>
  <label>Password: <input type="password" name="password" required></label><br>
  <button type="submit">Login</button>
</form>