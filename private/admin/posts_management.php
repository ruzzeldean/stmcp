<?php require_once '../includes/admin_auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin | Star Touring Motorcycle Club</title>
  <link rel="shortcut icon" href="../assets/img/logo/logo.png" type="image/x-icon">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- AdminLTE 4 -->
  <link rel="stylesheet" href="../assets/css/adminlte.min.css">
  <!-- DataTables -->
  <?php require_once '../includes/datatables/styles_include.php'; ?>
  <!-- Custom CSS -->
  <link rel="stylesheet" href="../assets/css/style.css">
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
        <img src="../assets/img/logo/logo.png" alt="STMCP Logo" class="brand-image">
        <span class="brand-text font-weight-light">ADMIN | STMCP</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="../assets/img/user-icon/user-icon.png" alt="User Icon">
          </div>
          <div class="info">
            <a href="#" class="d-block"><?php echo strtoupper($_SESSION['firstName']); ?></a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-header">HOME</li>

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
                <i class="nav-icon fa-solid fa-user-clock"></i>
                <p>
                  Aspirants
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="./official_members.php" class="nav-link">
                <i class="nav-icon fa-solid fa-user-check"></i>
                <p>
                  Official Members
                </p>
              </a>
            </li>

            <li class="nav-header">CLUB CONTENTS</li>

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

            <li class="nav-header">ACCOUNT</li>

            <li class="nav-item">
              <a href="./user_accounts.php" class="nav-link">
                <i class="nav-icon fa-solid fa-users"></i>
                <p>User Accounts</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="./settings.php" class="nav-link">
                <i class="nav-icon fa-solid fa-gear"></i>
                <p>Settings</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="../" class="nav-link">
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
          <div class="row mb-3">
            <a class="btn btn-app bg-primary" href="./create_post.php">
              <i class="fas fa-square-plus"></i> Create
            </a>
          </div> <!-- /.row -->

          <div class="xxl">
            <div class="card p-3">
              <div class="table-responsive">
                <table class="data-table table table-striped table-hover text-nowrap my-3">
                  <thead>
                    <tr>
                      <th></th>
                      <th>Title</th>
                      <th>Category</th>
                      <th>Image</th>
                      <th>Content</th>
                      <th>Created By</th>
                      <th>Created At</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    try {
                      $loadPosts = $conn->prepare(
                        'SELECT posts.*, admins.first_name, admins.last_name
                        FROM posts JOIN admins
                        WHERE posts.created_by = admins.admin_id'
                      );
                      $loadPosts->execute();

                      while ($row = $loadPosts->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                        <tr>
                          <td></td>
                          <td class="text-truncate" style="max-width: 150px;"><?php echo htmlspecialchars($row['title']); ?></td>
                          <td><?php echo htmlspecialchars($row['category']); ?></td>
                          <td><img class="img-thumbnail" src="/stmcp/uploads/posts/<?php echo htmlspecialchars($row['image_path']); ?>" alt=""></td>
                          <td class="text-truncate" style="max-width: 150px;"><?php echo htmlspecialchars($row['content']); ?></td>
                          <td><?php echo htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?></td>
                          <td>
                            <?php
                            $date = $row['created_at'];
                            $formatted = date('F j, Y g:i A', strtotime($date));
                            echo htmlspecialchars($formatted, ENT_QUOTES, 'UTF-8');
                            ?>
                          </td>
                          <td>
                            <button class="btn btn-primary">View</button>
                            <button class="btn btn-success">Approve</button>
                            <button class="btn btn-danger">Reject</button>
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
  <script src="../assets/js/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../assets/js/adminlte.min.js"></script>
  <!-- DataTables -->
  <?php require_once '../includes/datatables/scripts_include.php'; ?>
  <script src="../assets/js/data-tables.js"></script>
</body>

</html>