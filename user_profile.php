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
    $timeA = strtotime($a['created'] ?? '') ?: 0;
    $timeB = strtotime($b['created'] ?? '') ?: 0;
    return $timeB - $timeA;
});

include 'includes/header.php';
?>
<div class="page_wrapper">
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
                        <a href="post.php?id=<?= $post['id'] ?>">
                            <article class="post" >
                        
                                <p><?= htmlspecialchars($post['text']) ?></p>
                                
                                <?php if (!empty($post['image'])): ?>
                                    <img src="assets/uploads/<?= htmlspecialchars($post['image']) ?>" alt="post image">
                                <?php endif; ?>
                                <small>Posted on <?= $post['created'] ?></small>
                                <?php
                                    $likeText = "like";
                                    if (isset($_SESSION['user'])):
                                    $likeFile = __DIR__ . "/data/likes/{$post['id']}_{$_SESSION['user']['username']}.json";
                                    $liked = file_exists($likeFile);
                                    $likeText = $liked ? "Unlike" : "Like";
                                    endif;
                                ?>
                                <form method="post" action="like.php">
                                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                    <button type="submit"><?= $likeText ?></button>
                                    <?php
                                        $likes = glob(__DIR__ . "/data/likes/{$post['id']}_*.json");
                                    ?>
                                    <span><?= count($likes) ?> likes</span>
                                </form>

                                <?php if (file_exists('post.php')): ?>
                                    <div>
                                        <a class="post-actions"href="delete_post.php?id=<?= $post['id'] ?>&return_url=admin_panel.php" onclick="return confirm('Are you sure you want to delete this post?')">
                                            Delete post
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </article>
                        </a>
                        
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
        </div>
    </main>
</div>
<?php include 'includes/footer.php'; ?>