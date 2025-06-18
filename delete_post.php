<?php
session_start();
include 'includes/functions.php';

// Only allow admins to delete posts
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Get post ID and return URL
$postId = $_GET['id'] ?? '';
$returnUrl = $_GET['return_url'] ?? 'admin_panel.php';

if (empty($postId)) {
    header('Location: ' . $returnUrl);
    exit();
}

$deletionResults = [
    'post' => false,
    'comments' => 0,
    'likes' => 0,
    'comment_likes' => 0
];

// Delete the main post file
$postFile = "data/posts/{$postId}.json";
if (file_exists($postFile)) {
    $deletionResults['post'] = unlink($postFile);
}

// Delete all comments for this post
// Comments are named like: {postId}_{commentId}.json
$commentFiles = glob("data/comments/{$postId}_*.json");
foreach ($commentFiles as $commentFile) {
    $commentData = json_decode(file_get_contents($commentFile), true);
    if ($commentData && isset($commentData['id'])) {
        $commentId = $commentData['id'];
        
        // Delete likes for this comment
        // Comment likes are named like: {commentId}_{username}.json
        $commentLikeFiles = glob("data/likes/{$commentId}_*.json");
        foreach ($commentLikeFiles as $commentLikeFile) {
            if (unlink($commentLikeFile)) {
                $deletionResults['comment_likes']++;
            }
        }
    }
    
    // Delete the comment file itself
    if (unlink($commentFile)) {
        $deletionResults['comments']++;
    }
}

// Delete all likes for this post
// Post likes are named like: {postId}_{username}.json
$postLikeFiles = glob("data/likes/{$postId}_*.json");
foreach ($postLikeFiles as $likeFile) {
    if (unlink($likeFile)) {
        $deletionResults['likes']++;
    }
}

// Set a session message about the deletion results
$message = "Post deleted successfully.";
if ($deletionResults['comments'] > 0) {
    $message .= " Deleted {$deletionResults['comments']} comment(s).";
}
if ($deletionResults['likes'] > 0) {
    $message .= " Deleted {$deletionResults['likes']} post like(s).";
}
if ($deletionResults['comment_likes'] > 0) {
    $message .= " Deleted {$deletionResults['comment_likes']} comment like(s).";
}

if (!$deletionResults['post']) {
    $message = "Error: Could not delete post file.";
}

$_SESSION['message'] = $message;

// Redirect back to the referring page
header('Location: ' . $returnUrl);
exit();
?>