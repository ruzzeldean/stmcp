<?php
include __DIR__ . '/../includes/auth.php';

requireAdminModerator();

if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT) || $_GET['id'] <= 0) {
  error_log('Invalid request: Missing post ID');
  http_response_code(400);
  echo e('Invalid request');
  exit;
}

$postID = (int) $_GET['id'];

try {
  $db = new Database();

  $sql = 'SELECT posts.title, posts.category, posts.image_path, posts.content, posts.created_by
          FROM posts
          WHERE post_id = :post_id';
  $post = $db->fetchOne($sql, ['post_id' => $postID]);

  if (!$post) {
    // error_log('No post found');
    // redirect to 404 page - todo
    http_response_code(404);
    exit;
  }

  if ($post['created_by'] !== $_SESSION['user_id']) {
    header('Location: posts.php');
    exit;
  }
} catch (Throwable $e) {
  error_log('Error fetching post: ' . $e->getMessage());
  http_response_code(500);
  echo e('Something went wrong, please try again later');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include __DIR__ . '/../partials/head.php'; ?>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">
    <?php include __DIR__ . '/../partials/header.php'; ?>

    <?php include __DIR__ . '/../partials/aside.php'; ?>

    <main class="app-main">
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="mb-0">Update Post</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="posts.php">Posts</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <div class="app-content">
        <div class="container-fluid">
          <div class="card border-0 shadow-sm max-w-500">
            <div class="card-body">
              <form id="post-form" data-form-type="update-post-form" data-post-id="<?= e($postID) ?>" novalidate>
                <div class="form-group mb-3">
                  <label class="form-label" for="title">Title <span class="asterisk">*</span></label>
                  <input class="form-control" type="text" name="title" id="title" placeholder="Enter post title" value="<?= e($post['title']) ?>" required>
                  <div class="invalid-feedback"></div>
                </div>

                <div class="form-group mb-3">
                  <label class="form-label" for="category">Category <span class="asterisk">*</span></label>
                  <select name="category" id="category" class="form-select" required>
                    <option value="" selected disabled>Select category</option>
                    <option value="Announcement" <?= e($post['category'] === 'Announcement' ? 'selected' : '') ?>>Announcement</option>
                    <option value="Upcoming" <?= e($post['category'] === 'Upcoming' ? 'selected' : '') ?>>Upcoming</option>
                    <option value="Past Event" <?= e($post['category'] === 'Past Event' ? 'selected' : '') ?>>Past Event</option>
                  </select>
                  <div class="invalid-feedback"></div>
                </div>

                <div class="form-group mb-3">
                  <label class="form-label" for="image">Image <span class="asterisk">*</span></label>
                  <input class="form-control" type="file" id="image" name="image" accept="image/jpg, image/jpeg, image/png" required>
                  <div class="invalid-feedback"></div>
                  <img id="image-preview" class="img-fluid rounded mt-3" src="../storage/uploads/posts/<?= e($post['image_path']) ?>" alt="Image Preview">
                </div>

                <div class="form-group mb-3">
                  <label class="form-label" for="content">Content <span class="asterisk">*</span></label>
                  <textarea class="form-control" name="content" id="content" rows="3" placeholder="Enter post content..." required><?= e($post['content']) ?></textarea>
                  <div class="invalid-feedback"></div>
                </div>

                <div class="form-group mt-4 mb-2">
                  <button id="submit-btn" class="btn btn-primary w-100" data-csrf-token="<?= e($_SESSION['csrf_token']) ?>" type="submit">Update</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </main>

    <?php include __DIR__ . '/../partials/footer.php'; ?>
  </div>

  <?php include __DIR__ . '/../partials/scripts.php'; ?>
  <script src="../assets/js/posts/create_update_post.js"></script>
</body>

</html>