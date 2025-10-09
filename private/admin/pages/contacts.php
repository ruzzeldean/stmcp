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
          <div class="row">
            <div class="col-sm-6">
              <h1 class="m-0">Contacts</h1>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          <div class="row card overflow-hidden">
            <div class="card-header border-bottom-0 px-1">
              <div id="search-container" class="col-12">
                <input id="search-input" class="form-control" type="search" placeholder="Search contacts">
                <i id="search-icon" class="fa-solid fa-magnifying-glass"></i>
              </div>
            </div> <!-- /.card-header -->

            <div id="contacts-body" class="card-body px-2 py-2">
              <!-- Contacts goes here -->
            </div> <!-- /#contacts-body -->
          </div> <!-- /.row .card -->
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
  <script>
    const CURRENT_USER_ID = <?= e((int) $_SESSION['userID']) ?>;
  </script>
  <script src="../../assets/shared/js/contacts.js"></script>
</body>

</html>