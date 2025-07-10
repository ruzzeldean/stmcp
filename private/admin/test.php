<?php
require_once __DIR__ . '/../../config/connection.php';

$postId = 2;

try {
  $stmt = $conn->prepare('SELECT title, category, image_path, content, created_at FROM posts WHERE post_id = :id');
  $stmt->execute(['id' => $postId]);
  $post = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$post) {
    echo 'Post not found.';
    exit;
  }
} catch (Throwable $e) {
  echo 'Error fetching post.';
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <h1><?= htmlspecialchars($post['title']); ?></h1>
  <p class="text-muted"><?= htmlspecialchars($post['category']) ?> | <?= date('F j, Y', strtotime($post['created_at'])); ?></p>
  <img src="/stmcp/uploads/posts/<?= htmlspecialchars($post['image_path']); ?>" class="img-fluid mb-4" alt="Post Image">
  <div><?= nl2br(htmlspecialchars($post['content'])) ?></div>
</body>
</html>