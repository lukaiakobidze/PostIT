<?php
require_once  'includes/post_class.php';
function get_user_file($email) {
    return __DIR__ . '/../data/users/' . md5($email) . '.json';
}

function load_all_posts() {
    $dir = __DIR__ . '/../data/posts/';
    $posts = [];
    foreach (glob($dir . '*.json') as $file) {
        $data = json_decode(file_get_contents($file), true);
        $posts[] = new Post(
            $data['id'],
            $data['author'],
            $data['text'],
            $data['image'],
            $data['created'],
            $data['likes'] ?? []
        );
    }
    return $posts;
}