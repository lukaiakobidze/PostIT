<?php
function get_user_file($email) {
    return __DIR__ . '/../data/users/' . md5($email) . '.json';
}

function load_all_posts() {
    $dir = __DIR__ . '/../data/posts/';
    $posts = [];
    foreach (glob($dir . '*.json') as $file) {
        $posts[] = json_decode(file_get_contents($file), true);
    }
    return $posts;
}