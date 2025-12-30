<?php
include __DIR__ . '/../../config/database.php';

$postId = (int)$_GET['id'] ?? null;

if (!filter_var($postId, FILTER_VALIDATE_INT, [
  'options' => ['min_range' => 1]
])) {
  http_response_code(400);
  exit('Invalid request: Missing or invalid post ID');
}

try {
  $db = new Database();

  $sql = 'SELECT title, category, image_path, content, created_at
          FROM posts WHERE post_id = ? LIMIT 1';
  $post = $db->fetchOne($sql, [$postId]);
} catch (Throwable $e) {
  error_log("Error fetching post: $e");
}

$createdAt = new DateTime($post['created_at']);
$post['created_at'] = $createdAt->format('M. j, Y');

$category = $post['category'];
$badge = match ($category) {
  'Announcement' => 'info',
  'Upcoming' => 'primary',
  'Past Event' => 'success',
  default => 'secondary'
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
  <?php include __DIR__ . '/../partials/head.php'; ?>
</head>

<body>
  <div class="wrapper">
    <?php include __DIR__ . '/../partials/header.php'; ?>

    <main class="py-5">
      <div class="container py-5 d-flex">
        <div class="mx-auto" style="max-width: 700px !important">
          <h2 class=""><?= htmlspecialchars($post['title']) ?></h2>

          <div class="mb-3 text-muted">
            <small>Published on <?= htmlspecialchars($post['created_at']) ?> |
              <span class="badge rounded-pill text-bg-<?= $badge ?>">
                <?= htmlspecialchars($post['category']) ?>
              </span>
            </small>
          </div>

          <img src="../assets/<?= $post['image_path'] ?>"
            class="img-fluid max-w-700 rounded"
            alt="<?= htmlspecialchars($post['title']) ?>">

          <div class="ws-pre-wrap mt-4"><?= htmlspecialchars($post['content']) ?></div>
        </div>
      </div>
    </main>

    <?php include __DIR__ . '/../partials/footer.php'; ?>
  </div>

  <?php include __DIR__ . '/../partials/scripts.php'; ?>
</body>

</html>