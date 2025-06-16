<?php
session_start();

if (!isset($_SESSION['user']) || !isset($_POST['post_id'])) {
    header('Location: index.php');
    exit();
}

$username = $_SESSION['user']['username'];
$postId = $_POST['post_id'];
$likeFile = __DIR__ . "/data/likes/{$postId}_{$username}.json";

if (file_exists($likeFile)) {
    // Unlike
    unlink($likeFile);
} else {
    // Like
    $likeData = [
        'post_id' => $postId,
        'username' => $username
    ];
    file_put_contents($likeFile, json_encode($likeData));
}

header('Location: index.php');
exit();
