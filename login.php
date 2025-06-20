<?php
session_start();
include 'includes/header.php';
include 'includes/functions.php';
include 'includes/user_class.php';

if (isset($_GET['redirect'])) {
    $_SESSION['redirect_after_login'] = $_GET['redirect'];
}

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
        if (isset($_SESSION['redirect_after_login'])) {
            $redirect = $_SESSION['redirect_after_login'];
            unset($_SESSION['redirect_after_login']); // optional cleanup
            header("Location: $redirect");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        $error = "Invalid credentials.";
    }
}
?>
<div class="page_wrapper">
    <form method="POST">
    <h2>Login</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <label>Email: <input type="email" name="email" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <button type="submit">Login</button>
    </form>
</div>
<?php include 'includes/footer.php'; ?>