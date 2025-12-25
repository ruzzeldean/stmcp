<?php
include __DIR__ . '/../includes/auth.php';

requireAdminModerator();
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
              <h3 class="mb-0">Create Post</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="posts.php">Posts</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <div class="app-content">
        <div class="container-fluid">
          <div class="card border-0 shadow-sm max-w-500">
            <div class="card-body">
              <form id="post-form" data-form-type="create-post-form" novalidate>
                <div class="form-group mb-3">
                  <label class="form-label" for="title">Title <span class="asterisk">*</span></label>
                  <input class="form-control" type="text" name="title" id="title" placeholder="Enter post title" required>
                  <div class="invalid-feedback"></div>
                </div>

                <div class="form-group mb-3">
                  <label class="form-label" for="category">Category <span class="asterisk">*</span></label>
                  <select name="category" id="category" class="form-select" required>
                    <option value="" selected disabled>Select category</option>
                    <option value="Announcement">Announcement</option>
                    <option value="Upcoming">Upcoming</option>
                    <option value="Past Event">Past Event</option>
                  </select>
                  <div class="invalid-feedback"></div>
                </div>

                <div class="form-group mb-3">
                  <label class="form-label" for="image">Image <span class="asterisk">*</span></label>
                  <input class="form-control" type="file" id="image" name="image" accept="image/jpg, image/jpeg, image/png" required>
                  <div class="invalid-feedback"></div>
                  <img id="image-preview" class="d-none img-fluid rounded mt-3" src="" alt="Image Preview">
                </div>

                <div class="form-group mb-3">
                  <label class="form-label" for="content">Content <span class="asterisk">*</span></label>
                  <textarea class="form-control" name="content" id="content" rows="3" placeholder="Enter post content..." required></textarea>
                  <div class="invalid-feedback"></div>
                </div>

                <div class="form-group mt-4 mb-2">
                  <button id="submit-btn" class="btn btn-primary w-100" data-csrf-token="<?= e($_SESSION['csrf_token']) ?>" type="submit">Create</button>
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