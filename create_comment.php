<?php
session_start();
include 'includes/comment_class.php';

if (!isset($_SESSION['user']) || !isset($_POST['post_id'], $_POST['text'])) {
    header('Location: index.php');
    exit();
}

$postId = $_POST['post_id'];
$text = trim($_POST['text']);
if ($text === '') {
    header("Location: post.php?id=" . $postId);
    exit();
}

// Optional: support image uploads later
$image = ''; // For now, empty string if no image is allowed

$commentId = uniqid("com_", true);
$author = $_SESSION['user']['username'];
$created = date('Y-m-d H:i:s');

$comment = new Comment($commentId, $author, $text, $image, $created);

// Save it as JSON
file_put_contents(__DIR__ . "/data/comments/{$postId}_{$commentId}.json", json_encode($comment));

header("Location: post.php?id=" . $postId);
exit();
