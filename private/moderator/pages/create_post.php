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
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Moderator | Star Touring Motorcycle Club</title>
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
        <span class="brand-text font-weight-light">MODERATOR | STMCP</span>
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
              <a href="./aspirants.php" class="nav-link">
                <i class="nav-icon fa-solid fa-user-check"></i>
                <p>
                  Aspirants
                </p>
              </a>
            </li>

            <li class="nav-header">CONTENT MANAGEMENT</li>

            <li class="nav-item">
              <a href="./posts.php" class="nav-link active">
                <i class="nav-icon fa-solid fa-newspaper"></i>
                <p>Posts</p>
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
              <h1 class="m-0">Create Post</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="./posts.php">Posts</a></li>
                <li class="breadcrumb-item active">Create</li>
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
                    <input class="form-control" type="text" id="title" placeholder="Enter title (max: 100 characters)" maxlength="100">
                  </div>

                  <div class="form-group">
                    <label for="category">Category</label>
                    <select class="custom-select" id="category">
                      <option value="" selected disabled>Select category</option>
                      <option value="Upcoming">Upcoming</option>
                      <option value="Past Event">Past Event</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="image">Image</label>
                    <input class="form-control-file border rounded p-1" type="file" id="image" accept="image/*">
                    <br>
                    <img id="image-preview" class="d-none img-fluid rounded" src="" alt="Image Preview">
                  </div>

                  <div class="form-group">
                    <label for="content">Content</label>
                    <textarea class="form-control" id="content" rows="5" placeholder="Post content..." maxlength="5000"></textarea>
                  </div>

                  <div class="form-group">
                    <button id="create-btn" class="btn btn-primary w-100" data-csrf-token="<?php echo e($csrfToken); ?>">Create</button>
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

  <!-- REQUIRED SCRIPTS -->

  <!-- jQuery -->
  <script src="../../assets/shared/js/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="../../assets/shared/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../../assets/shared/js/adminlte.min.js"></script>
  <!-- Custom script -->
   <script src="../../assets/moderator/js/posts/create_post.js"></script>
</body>

</html>