<?php
session_start();
include 'includes/header.php';
include 'includes/functions.php';
include 'includes/user_class.php';

$posts = load_all_posts();
?>

<main>
  <h1>Welcome to PostIt</h1>
  <?php if (isset($_SESSION['user'])): ?>
    <a href="create_post.php">+ New Post</a>
    <a href="logout.php">Logout</a>
    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
      <a href="admin_panel.php">Admin Panel</a>
    <?php endif; ?>
  <?php else: ?>
    <a href="login.php">Login</a> or <a href="register.php">Register</a>
  <?php endif; ?>

  <section class="posts">
    <?php foreach (array_reverse($posts) as $post): ?>
      <article class="post">
        <h2><?= htmlspecialchars($post->author) ?></h2>
        <a href="post.php?id=<?= $post->id ?>">
          <p><?= nl2br(htmlspecialchars($post->text)) ?></p>
          <?php if (!empty($post->image)): ?>
            <img src="assets/uploads/<?= htmlspecialchars($post->image) ?>" alt="post image" />
          <?php endif; ?>
          <small>Posted on <?= $post->created ?></small>
        </a>
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
        <hr>
      </article>
    <?php endforeach; ?>
  </section>
</main>

<?php include 'includes/footer.php'; ?>