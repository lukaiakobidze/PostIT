<?php
session_start();
include 'includes/header.php';
include 'includes/functions.php';
include 'includes/comment_class.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$postId = $_GET['id'];
$posts = load_all_posts();
$post = null;
foreach ($posts as $p) {
    if ($p->id == $postId) {
        $post = $p;
        break;
    }
}

if (!$post) {
    echo "<p>Post not found</p>";
    include 'includes/footer.php';
    exit();
}

// Load comments
$commentFiles = glob(__DIR__ . "/data/comments/{$postId}_*.json");
$comments = [];
foreach ($commentFiles as $file) {
    $comments[] = json_decode(file_get_contents($file));
}
?>

<div class="page_wrapper">
  <main>
    <article >
      <article class="post">
        <h2>Post by <?= htmlspecialchars($post->author) ?></h2>
        <p><?= nl2br(htmlspecialchars($post->text)) ?></p>
        <?php if (!empty($post->image)): ?>
          <img src="assets/uploads/<?= htmlspecialchars($post->image) ?>" />
        <?php endif; ?>
        <small>Posted on <?= $post->created ?></small>
        <br>
        <?php if (isset($_SESSION['user'])): ?>
      
      
        <!-- Like Button -->
        <?php
          $likeText = "like";
          if (isset($_SESSION['user'])):
            $likeFile = __DIR__ . "/data/likes/{$post->id}_{$_SESSION['user']['username']}.json";
            $liked = file_exists($likeFile);
            $likeText = $liked ? "Unlike" : "Like";
          endif;
        ?>
        <form method="post" action="like.php">
          <input type="hidden" name="post_id" value="<?= $post->id ?>">
          <button type="submit"><?= $likeText ?></button>
          <?php
            $likes = glob(__DIR__ . "/data/likes/{$post->id}_*.json");
          ?>
          <span><?= count($likes) ?> likes</span>
        </form>
        
      </article>
    </article>
    
    
    <!-- Create Comment -->
    <article class="create_comment">
        <form method="post" action="create_comment.php">
          <input type="hidden" name="post_id" value="<?= $postId ?>">
          <textarea name="text" placeholder="make a comment." required></textarea>
          <button type="submit">Comment</button>
        </form>
        <?php else: ?>
          <a class="button" href="login.php?redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>"> Log in to comment </a>
        <?php endif; ?>
    </article>
    

    <!-- Comments -->
    <h2>Comments</h2>
    <button class="toggle-comments">Show Comments</button>
    <div class="comments-section">
      <?php foreach ($comments as $comment): ?>
        <div class="comment">
          <p><strong><?= htmlspecialchars($comment->author) ?></strong>: <?= nl2br(htmlspecialchars($comment->text)) ?></p>
          <div class="line_flex">
            <form method="post" action="com_like.php">
              <input type="hidden" name="com_id" value="<?= $comment->id ?>">
              <button type="submit">Like</button>
              <?php
                $likeFiles = glob(__DIR__ . "/data/likes/{$comment->id}_*.json");
              ?>
              <span><?= count($likeFiles) ?> likes</span>
            </form>
            <small> <?= $comment->created ?></small>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    
  </main>
</div>
<?php include 'includes/footer.php'; ?>
