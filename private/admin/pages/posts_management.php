<?php
require_once __DIR__ . '/../../includes/admin_auth_check.php';

if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['csrf_token'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once __DIR__ . '/../../includes/admin/head.php'; ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
  <div class="wrapper">

    <?php require_once __DIR__ . '/../../includes/admin/aside.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Posts Management</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Posts Management</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          <div class="xxl">
            <div class="card p-3">
              <div class="card-header border-bottom-0">
                <select id="post-status" class="custom-select" style="max-width: 150px;">
                  <option value="">All Posts</option>
                  <option value="Pending" selected>Pending</option>
                  <option value="Published" class="text-success">Published</option>
                  <option value="Rejected" class="text-danger">Rejected</option>
                </select>
              </div>
              <div class="table-responsive">
                <table class="data-table table table-striped table-hover text-nowrap my-3">
                  <thead>
                    <tr>
                      <th></th>
                      <th>Title</th>
                      <th>Category</th>
                      <th>Image</th>
                      <th>Content</th>
                      <th>Status</th>
                      <th>Reason</th>
                      <th>Created By</th>
                      <th>Created At</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    try {
                      $sql =
                        'SELECT
                          posts.*,
                          om.first_name,
                          om.last_name
                        FROM posts
                        JOIN users ON posts.created_by = users.user_id
                        JOIN official_members AS om ON users.member_id = om.member_id';

                      $posts = $db->fetchAll($sql);

                      foreach ($posts as $post) :
                        $status = $post['status'];
                        $badge = match ($status) {
                          'Pending' => 'secondary',
                          'Published' => 'success',
                          'Rejected' => 'danger',
                          default => 'secondary'
                        };

                        $date = $post['created_at'];
                        $formatted = date('F j, Y g:i A', strtotime($date));
                    ?>
                        <tr>
                          <td></td>
                          <td class="text-truncate" style="max-width: 150px;"><?= e($post['title']) ?></td>
                          <td><?= e($post['category']) ?></td>
                          <td><img class="img-thumbnail" src="/stmcp/uploads/posts/<?= e($post['image_path']) ?>" alt="Post image"></td>
                          <td class="text-truncate" style="max-width: 150px;"><?= e($post['content']) ?></td>
                          <td>

                            <span class="badge badge-<?= e($badge) ?>"><?= e($status) ?></span>
                          </td>
                          <td><?= e($post['reason']) ?></td>
                          <td><?= e($post['first_name'] . " " . $post['last_name']) ?></td>
                          <td>
                            <?= e($formatted, ENT_QUOTES, 'UTF-8') ?>
                          </td>
                          <td>
                            <button class="preview-btn btn btn-primary" title="Preview" data-post-id="<?= e($post['post_id']) ?>" data-csrf-token="<?= e($csrfToken) ?>">Preview</button>
                          </td>
                        </tr>
                    <?php
                      endforeach;
                    } catch (Throwable $e) {
                      error_log('Error fetching posts (admin): ' . $e->getMessage());
                      echo '<div class="alert alert-warning" role="alert">An error occured while fetching data. Please try again later.</div>';
                    }
                    ?>
                  </tbody>
                </table>
              </div> <!-- /.table-responsive -->
            </div> <!-- /.card -->
          </div> <!-- /.xxl -->
        </div><!-- /.container-fluid -->

        <div class="modal fade" id="preview-modal" tabindex="-1" data-backdrop="static" data-keyboard="false" aria-labelledby="modalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Preview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <div class="modal-body">
                <h3 id="post-title" class="mb-1"></h3>
                <div>
                  <small id="post-date" class="badge text-muted"></small> | <span id="post-category" class="badge badge-secondary"></span>
                </div>

                <img id="post-image" class="img-fluid rounded my-3" src="" alt="Post image">

                <div id="post-content"></div>
              </div>
              <div class="modal-footer">
                <button type="button" class="approve-btn btn btn-success">Approve</button>
                <button type="button" class="reject-btn btn btn-danger">Reject</button>
              </div>
            </div>
          </div>
        </div> <!-- /.modal -->
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

  <!-- REQUIRED SCRIPTS -->

  <?php require_once __DIR__ . '/../../includes/admin/scripts.php' ?>
  <script src="../../assets/admin/js/posts/posts_management.js"></script>
</body>

</html>