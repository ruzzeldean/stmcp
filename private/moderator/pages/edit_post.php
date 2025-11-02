<?php
require_once __DIR__ . '/../../includes/moderator_auth_check.php';

if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT) || $_GET['id'] <= 0) {
  error_log('Invalid request: Missing post ID');
  http_response_code(400);
  echo e('Invalid request');
  exit;
}

$postID = (int) $_GET['id'];

if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['csrf_token'];

try {
  $sql = 'SELECT posts.title, posts.category, posts.image_path, posts.content
          FROM posts
          WHERE post_id = :post_id';
  $post = $db->fetchOne($sql, ['post_id' => $postID]);

  if (!$post) {
    error_log('No post found');
    http_response_code(404);
    exit;
  }
} catch (Throwable $e) {
  error_log('Error fetching post: ' . $e->getMessage());
  http_response_code(500);
  echo e('Something went wrong, please try again later');
  exit;
}

$title = $post['title'];
$category = $post['category'];
$image = $post['image_path'];
$content = $post['content'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once __DIR__ . '/../../includes/moderator/head.php'; ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
  <div class="wrapper">

    <?php require_once __DIR__ . '/../../includes/moderator/aside.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Edit Post</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="./posts.php">Posts</a></li>
                <li class="breadcrumb-item active">Edit</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-8 col-lg-6">
              <div class="card">
                <div class="card-body">
                  <div class="form-group">
                    <label for="title">Post Title</label>
                    <input class="form-control" type="text" id="title" placeholder="Enter title (max: 100 characters)" maxlength="100" value="<?php echo e($title); ?>">
                  </div>

                  <div class="form-group">
                    <label for="category">Category</label>
                    <select class="custom-select" id="category">
                      <option value="" selected disabled>Select category</option>
                      <option value="Upcoming" <?php echo $category === 'Upcoming' ? 'selected' : ''; ?>>Upcoming</option>
                      <option value="Past Event" <?php echo ($category === 'Past Event' ? 'selected' : ''); ?>>Past Event</option>
                    </select>
                  </div>

                  <!-- <p><?php echo e($image); ?></p> -->

                  <div class="form-group">
                    <label for="image">Image</label>
                    <input class="form-control-file border rounded p-1" type="file" id="image" accept="image/*">
                    <br>
                    <img id="image-preview" class="img-fluid rounded" src="/stmcp/uploads/posts/<?php echo e($image); ?>" alt="Image Preview">
                  </div>

                  <div class="form-group">
                    <label for="content">Content</label>
                    <textarea class="form-control" id="content" rows="5" placeholder="Post content..." maxlength="5000"><?php echo e($content); ?></textarea>
                  </div>

                  <div class="form-group">
                    <button id="update-btn" class="btn btn-primary w-100" data-post-id="<?php echo e($postID); ?>" data-csrf-token="<?php echo e($csrfToken); ?>">Update</button>
                  </div>
                </div> <!-- /.card-body -->
              </div> <!-- /.card -->
            </div> <!-- /.col -->
          </div> <!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
      <strong>&copy; 2025 <span class="text-warning">STMCP</span>. All rights reserved.
    </footer>
  </div>
  <!-- ./wrapper -->

  <?php require_once __DIR__ . '/../../includes/moderator/scripts.php'; ?>
  <script src="../../assets/moderator/js/posts/update_post.js"></script>
</body>

</html>