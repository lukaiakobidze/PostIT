<?php
session_start();

if (!isset($_SESSION['user']) || !isset($_POST['com_id'])) {
    header('Location: index.php');
    exit();
}

$username = $_SESSION['user']['username'];
$comId = $_POST['com_id'];
$likeFile = __DIR__ . "/data/likes/{$comId}_{$username}.json";

if (file_exists($likeFile)) {
    // Unlike
    unlink($likeFile);
} else {
    // Like
    $likeData = [
        'com_id' => $comId,
        'username' => $username
    ];
    file_put_contents($likeFile, json_encode($likeData));
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
