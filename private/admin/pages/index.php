<?php require_once __DIR__ . '/../../includes/admin_auth_check.php'; ?>
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
          <!-- Info boxes -->
          <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fa-solid fa-user-check"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Official Members</span>
                  <span class="info-box-number">
                    <?php
                    try {
                      $sql = 'SELECT COUNT(*) AS total FROM official_members';
                      $res = $db->fetchOne($sql);

                      echo e($res['total']);
                    } catch (Throwable $ex) {
                      error_log("Count query failed: " . $ex->getMessage());
                      echo 'Unable to retrieve official member count. Please try again later.';
                    }
                    ?>
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix hidden-md-up"></div>

            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-primary elevation-1"><i class="fa-solid fa-user-shield"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Admins</span>
                  <span class="info-box-number">
                    <?php
                    try {
                      $sql = 'SELECT COUNT(*) AS total FROM users WHERE role_id = 2';
                      $res = $db->fetchOne($sql);

                      echo e($res['total']);
                    } catch (Throwable $ex) {
                      error_log("Count query failed: " . $ex->getMessage());
                      echo 'Unable to retrieve admin count. Please try again later.';
                    }
                    ?>
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-secondary elevation-1"><i class="fa-solid fa-user-pen"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Moderators</span>
                  <span class="info-box-number">
                    <?php
                    try {
                      $sql = 'SELECT COUNT(*) AS total FROM users WHERE role_id = 3';
                      $res = $db->fetchOne($sql);

                      echo e($res['total']);
                    } catch (Throwable $ex) {
                      error_log("Count query failed: " .  $ex->getMessage());
                      echo 'Unable to retrieve moderator count. Please try again later.';
                    }
                    ?>
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
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

  <?php require_once __DIR__ . '/../../includes/admin/scripts.php'; ?>
</body>

</html>