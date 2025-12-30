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

    <main id="posts-main-con" class="app-main" data-csrf-token="<?= $csrfToken ?>">
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="mb-0">Posts</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item active" aria-current="page">Posts</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <div class="app-content">
        <div class="container-fluid">
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <div class="table-responsive">
                <table class="data-table display table table-borderless table-striped table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Title</th>
                      <th>Category</th>
                      <th>Image</th>
                      <th>Status</th>
                      <th>Created By</th>
                      <th>Created At</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody id="table-body" data-user-name="<?= e($_SESSION['full_name']) ?>" data-user-role="<?= e($userRole) ?>">
                    <tr>
                      <td colspan="100%" class="text-muted">
                        Loading...
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="row row-gap-2">
                <div class="col-md-6 order-md-last">
                  <nav class="">
                    <ul id="pagination-controls"
                      class="pagination pagination-sm justify-content-center justify-content-md-end mb-0 mt-3 mt-lg-0"></ul>
                  </nav>
                </div>

                <div id="pagination-info"
                  class="col-md-6 d-flex align-items-center justify-content-center justify-content-md-start">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <a id="create-post-btn" class="btn btn-primary rounded-circle" href="create_post.php">
        <i class="fa-solid fa-plus"></i>
      </a>
    </main>

    <?php include __DIR__ . '/../partials/footer.php'; ?>
  </div>

  <div class="modal fade" id="preview-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Preview</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body"></div>
      </div>
    </div>
  </div>

  <?php include __DIR__ . '/../partials/scripts.php'; ?>
  <script src="../assets/js/posts/posts.js"></script>
</body>

</html>