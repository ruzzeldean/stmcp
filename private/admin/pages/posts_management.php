<?php
require_once __DIR__ . '/../../includes/admin_auth_check.php';

if (empty($_SESSION['csrfToken'])) {
  $_SESSION['csrfToken'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['csrfToken'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin | Star Touring Motorcycle Club</title>
  <link rel="shortcut icon" href="../../assets/shared/images/logo/logo.png" type="image/x-icon">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- AdminLTE 4 -->
  <link rel="stylesheet" href="../../assets/shared/css/adminlte.min.css">
  <!-- DataTables -->
  <?php require_once __DIR__ . '/../../includes/datatables/styles_include.php'; ?>
  <!-- Custom CSS -->
  <link rel="stylesheet" href="../../assets/shared/css/style.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="./" class="brand-link">
        <img src="../../assets/shared/images/logo/logo.png" alt="STMCP Logo" class="brand-image">
        <span class="brand-text font-weight-light">ADMIN | STMCP</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="../../assets/shared/images/user-icon/user-icon.png" alt="User Icon">
          </div>
          <div class="info">
            <a href="#" class="d-block"><?php echo e(strtoupper($_SESSION['firstName'])); ?></a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-header">MAIN</li>

            <li class="nav-item">
              <a href="./" class="nav-link">
                <i class="nav-icon fa-solid fa-gauge-high"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>

            <li class="nav-header">MEMBERS</li>

            <li class="nav-item">
              <a href="./official_members.php" class="nav-link">
                <i class="nav-icon fa-solid fa-user-check"></i>
                <p>
                  Official Members
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="./user_accounts.php" class="nav-link">
                <i class="nav-icon fa-solid fa-users"></i>
                <p>User Accounts</p>
              </a>
            </li>

            <li class="nav-header">CONTENT MANAGEMENT</li>

            <li class="nav-item">
              <a href="./posts_management.php" class="nav-link active">
                <i class="nav-icon fa-solid fa-newspaper"></i>
                <p>Posts Management</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="./donations.php" class="nav-link">
                <i class="nav-icon fa-solid fa-coins"></i>
                <p>Donations</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="./donations.php" class="nav-link">
                <i class="nav-icon fa-solid fa-tags"></i>
                <p>Merchandise</p>
              </a>
            </li>

            <li class="nav-header">SYSTEM</li>

            <li class="nav-item">
              <a href="./settings.php" class="nav-link">
                <i class="nav-icon fa-solid fa-gear"></i>
                <p>Settings</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="../../" class="nav-link">
                <i class="nav-icon fa-solid fa-arrow-right-from-bracket"></i>
                <p>Logout</p>
              </a>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

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
                <select id="status" class="custom-select" style="max-width: 150px;">
                  <option value="" disabled>Post Status</option>
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
                      <th>Created By</th>
                      <th>Created At</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    try {
                      $loadPosts = $conn->prepare(
                        'SELECT posts.*, users.first_name, users.last_name
                        FROM posts JOIN users
                        WHERE posts.created_by = users.user_id'
                      );
                      $loadPosts->execute();

                      while ($row = $loadPosts->fetch()) {
                    ?>
                        <tr>
                          <td></td>
                          <td class="text-truncate" style="max-width: 150px;"><?php echo e($row['title']); ?></td>
                          <td><?php echo e($row['category']); ?></td>
                          <td><img class="img-thumbnail" src="/stmcp/uploads/posts/<?php echo e($row['image_path']); ?>" alt="Post image"></td>
                          <td class="text-truncate" style="max-width: 150px;"><?php echo e($row['content']); ?></td>
                          <td>
                            <?php
                              $status = $row['status'];
                              $badge = match($status) {
                                'Pending' => 'secondary',
                                'Published' => 'success',
                                'Rejected' => 'danger',
                                default => 'secondary'
                              }
                            ?>
                            <span class="badge badge-<?php echo e($badge); ?>"><?php echo e($status); ?></span>
                          </td>
                          <td><?php echo e($row['reason']); ?></td>
                          <td><?php echo e($row['first_name'] . " " . $row['last_name']); ?></td>
                          <td>
                            <?php
                            $date = $row['created_at'];
                            $formatted = date('F j, Y g:i A', strtotime($date));
                            echo e($formatted, ENT_QUOTES, 'UTF-8');
                            ?>
                          </td>
                          <td>
                            <button class="preview-btn btn btn-primary" title="Preview" data-post-id="<?php echo e($row['post_id']); ?>" data-csrf-token="<?php echo e($csrfToken); ?>">Preview</button>
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

  <!-- jQuery -->
  <script src="../../assets/shared/js/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="../../assets/shared/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../../assets/shared/js/adminlte.min.js"></script>
  <!-- DataTables -->
  <?php require_once __DIR__ . '/../../includes/datatables/scripts_include.php'; ?>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.0/dist/sweetalert2.all.min.js"></script>
  <script src="../../assets/shared/js/data_tables.js"></script>
  <script src="../../assets/admin/js/posts/posts_management.js"></script>
</body>

</html>