<?php
session_start();
include 'includes/functions.php';
require_once 'includes/post_class.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = $_POST['text'];
    $image = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], 'assets/uploads/' . $image);
    }

    $id = uniqid();

    $post = new Post(id: $id, author: $_SESSION['user']['username'], text: $text, image: $image, created: date('Y-m-d H:i:s'), likes: []);

    file_put_contents("data/posts/{$id}.json", json_encode($post));
    header('Location: index.php');
    exit();
}
include 'includes/header.php';
?>


<div class="page_wrapper">
    <form method="POST" enctype="multipart/form-data">
        <h2>New Post</h2>
        <label>Text:<br><textarea name="text" rows="5" cols="40" required></textarea></label><br>
        <label>Image: 
            <label class="custom-file-upload">
                Upload Image
                <input type="file" name="image" accept="image/*" hidden>
            </label>
        </label><br>
        <button type="submit">Post</button>
    </form> 
</div>
<?php include 'includes/footer.php'; ?>