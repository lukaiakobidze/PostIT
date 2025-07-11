<?php
session_start();
include 'includes/header.php';
include 'includes/functions.php';
include 'includes/user_class.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $username = $_POST['username'];
    if (isset($_POST['role'])) {
        $role = $_POST['role'];
    }
    else {
        $role = 'user';
    }
    

    $user = new User(email: $email, username: $username, role: $role);
    if ($user->register(password: $password)) {
        header(header: 'Location: login.php');
        exit();
    } else {
        $error = "User already exists.";
    }
}
?>
<div class="page_wrapper">
    <form method="POST">
    <h2>Register</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <label>Email: <input type="email" name="email" required></label><br>
    <label>Username: <input type="text" name="username" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <label class="custom-checkbox">
        <input type="checkbox" name="role" value="admin">
        <span class="checkmark"></span>
        Admin
    </label>
    <!-- <label>Admin: <input type="checkbox" name="role" value="admin" ></label><br> -->
    <button type="submit">Register</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>