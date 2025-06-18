<?php
session_start();
include 'includes/functions.php';

// Get username from URL parameter
$username = $_GET['username'] ?? '';

if (empty($username)) {
    header('Location: index.php');
    exit();
}

// Find the user data
$userData = null;
foreach (glob('data/users/*.json') as $file) {
    $data = json_decode(file_get_contents($file), true);
    if ($data['username'] === $username) {
        $userData = $data;
        break;
    }
}

if (!$userData) {
    header('Location: index.php');
    exit();
}

// Load all posts by this user
$userPosts = [];
if (file_exists('data/posts/')) {
    foreach (glob('data/posts/*.json') as $file) {
        $post = json_decode(file_get_contents($file), true);
        if ($post && isset($post['author']) && $post['author'] === $username) {
            $userPosts[] = $post;
        }
    }
}

// Sort posts by created timestamp (newest first)
usort($userPosts, function($a, $b) {
    $timeA = $a['created'] ?? 0;
    $timeB = $b['created'] ?? 0;
    return $timeB - $timeA;
});

include 'includes/header.php';
?>

<main>
    <div class="user-profile">
        <h1><?= htmlspecialchars($userData['username']) ?>'s Profile</h1>
        
        <div class="user-info">
            <p><strong>Username:</strong> <?= htmlspecialchars($userData['username']) ?></p>
            <p><strong>Role:</strong> <?= htmlspecialchars($userData['role']) ?></p>
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                <p><strong>Email:</strong> <?= htmlspecialchars($userData['email']) ?></p>
            <?php endif; ?>
        </div>

        <h2>Posts by <?= htmlspecialchars($userData['username']) ?> (<?= count($userPosts) ?>)</h2>
        
        <?php if (empty($userPosts)): ?>
            <p>This user hasn't posted anything yet.</p>
        <?php else: ?>
            <div class="posts-list">
                <?php foreach ($userPosts as $post): ?>
                    <hr>
                    <div class="post" >
                        <a href="post.php?id=<?= $post['id'] ?>">
                            <p><?= htmlspecialchars($post['text']) ?></p>
                            
                            <?php if (isset($post['image'])): ?>
                                <div class="post-image">
                                    <img src="assets/uploads/<?= htmlspecialchars($post['image']) ?>" alt="post image">
                                </div>
                            <?php endif; ?>
                            <small>Posted on <?= $post['created'] ?></small>
                        </a>
                        <?php
                            $likes = glob(__DIR__ . "/data/likes/{$post['id']}_*.json");
                        ?>
                        <span><?= count($likes) ?> likes</span>
                        <?php if (file_exists('post.php')): ?>
                            <div class="post-actions">
                                <a href="post.php?id=<?= htmlspecialchars($post['id']) ?>">View Full Post | </a>
                                <a href="delete_post.php?id=<?= $post['id'] ?>&return_url=admin_panel.php" onclick="return confirm('Are you sure you want to delete this post?')">
                                    Delete post
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
    </div>
</main>

<?php include 'includes/footer.php'; ?>