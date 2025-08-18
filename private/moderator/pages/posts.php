<?php
require_once __DIR__ . '/../../includes/moderator_auth_check.php';

if (empty($_SESSION['csrfToken'])) {
  $_SESSION['csrfToken'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['csrfToken'];
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
              <h1 class="m-0">Posts</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Posts</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          <div class="row mb-3">
            <a class="btn btn-app bg-primary pb-5" href="create_post.php">
              <i class="fa-solid fa-square-plus"></i> Create
            </a>
          </div> <!-- /.row -->

          <div class="xxl">
            <div class="card p-3">
              <div class="card-header border-bottom-0">
                <select id="post-status" class="custom-select" style="max-width: 150px;">
                  <option value="">All Post</option>
                  <option value="Pending">Pending</option>
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
                      <th>Created At</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    try {
                      $loadPosts = $conn->prepare(
                        'SELECT posts.*, users.first_name, users.last_name
                        FROM posts
                        JOIN users ON posts.created_by = users.user_id
                        WHERE posts.created_by = :user_id'
                      );
                      $loadPosts->execute(['user_id' => $_SESSION['userID']]);

                      while ($row = $loadPosts->fetch()) {
                    ?>
                        <tr>
                          <td></td>
                          <td class="text-truncate" style="max-width: 150px;"><?php echo e($row['title']); ?></td>
                          <td><?php echo e($row['category']); ?></td>
                          <td><img class="img-thumbnail" src="/stmcp/uploads/posts/<?php echo e($row['image_path']); ?>" alt="Post image" style="max-width: 100px;"></td>
                          <td class="text-truncate"><?php echo e($row['content']); ?></td>
                          <td>
                            <?php
                            $status = $row['status'];
                            $badge = match ($status) {
                              'Pending' => 'secondary',
                              'Published' => 'success',
                              'Rejected' => 'danger',
                              default => 'secondary'
                            }
                            ?>
                            <span class="badge badge-<?php echo e($badge); ?>"><?php echo e($status); ?></span>
                          </td>
                          <td><?php echo e($row['reason']); ?></td>
                          <td>
                            <?php
                            $date = $row['created_at'];
                            $formatted = date('F j, Y g:i A', strtotime($date));
                            echo e($formatted, ENT_QUOTES, 'UTF-8');
                            ?>
                          </td>
                          <td>
                            <button class="preview-btn btn btn-primary" title="Preview" data-post-id="<?php echo e($row['post_id']); ?>" data-csrf-token="<?php echo e($csrfToken); ?>"><i class="fa-solid fa-eye"></i></button>

                            <a class="btn btn-secondary" href="./edit_post.php?id=<?php echo e($row['post_id']); ?>" title="Edit" data-post-id="<?php echo e($row['post_id']); ?>"><i class="fa-solid fa-pen-to-square"></i></a>

                            <button class="delete-btn btn btn-danger" title="Delete" data-post-id="<?php echo e($row['post_id']); ?>" data-csrf-token="<?php echo e($csrfToken); ?>"><i class="fa-solid fa-trash"></i></button>
                          </td>
                        </tr>
                    <?php
                      }
                    } catch (Throwable $ex) {
                      error_log('Error fetching posts (admin): ' . $ex->getMessage());
                      echo '<div class="alert alert-warning" role="alert">An error occured while fetching data. Please try again later.</div>';
                    }
                    ?>
                  </tbody>
                </table>
              </div> <!-- /.table-responsive -->
            </div> <!-- /.card -->
          </div> <!-- /.xxl -->
        </div><!-- /.container-fluid -->
      </div>

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
              <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div> <!-- /.modal -->
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
  <script src="../../assets/moderator/js/posts/preview_post.js"></script>
  <script src="../../assets/moderator/js/posts/delete_post.js"></script>
</body>

</html>