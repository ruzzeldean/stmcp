<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin | Star Touring Motorcycle Club</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- AdminLTE 4 -->
  <link rel="stylesheet" href="../assets/css/adminlte.min.css">
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

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
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
            <a href="#" class="d-block">FIRST NAME</a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-header">HOME</li>

            <li class="nav-item">
              <a href="./" class="nav-link active">
                <i class="nav-icon fa-solid fa-gauge-high"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>

            <li class="nav-header">MEMBERS</li>

            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fa-solid fa-users"></i>
                <p>
                  Members
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>

              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="./aspirants.php" class="nav-link">
                    <i class="nav-icon fa-solid fa-user-clock"></i>
                    <p>
                      Aspirants
                      <span class="right badge badge-info">3</span>
                    </p>
                  </a>
                </li>

                <li class="nav-item">
                  <a href="./official_members.php" class="nav-link">
                    <i class="nav-icon fa-solid fa-user-check"></i>
                    <p>Official Members</p>
                  </a>
                </li>
              </ul>
            </li>

            <li class="nav-header">POSTS & DONATIONS</li>

            <li class="nav-item">
              <a href="./posts.php" class="nav-link">
                <i class="nav-icon fa-solid fa-newspaper"></i>
                <p>Posts</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="./donations.php" class="nav-link">
                <i class="nav-icon fa-solid fa-donate"></i>
                <p>Donations</p>
              </a>
            </li>

            <li class="nav-header">CONFIGURATIONS</li>

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
              <h1 class="m-0">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Dashboard</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
      <!-- To the right -->
      <!-- <div class="float-right d-none d-sm-inline">
        Anything you want
      </div> -->
      <!-- Default to the left -->
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
</body>

</html>