<?php
require_once __DIR__ . '/../../includes/admin_auth_check.php';

if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
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
              <h1 class="m-0">Create Donation</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="./donations.php">Donations</a></li>
                <li class="breadcrumb-item active">Create</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid pb-1">
          <div class="card shadow-sm max-w-500">
            <div class="card-body">
              <form id="create-donation-form" novalidate>
                <div class="form-group">
                  <label for="purpose">Purpose <span class="asterisk">*</span></label>
                  <input class="form-control" type="text" name="purpose" id="purpose" placeholder="Enter donation purpose">
                  <div class="invalid-feedback"></div>
                </div>

                <div class="form-group">
                  <label for="description">Description</label>
                  <textarea class="form-control" name="description" id="description" rows="3" placeholder="Enter description"></textarea>
                </div>

                <div class="form-group">
                  <label for="start-date">Start Date <span class="asterisk">*</span></label>
                  <input class="form-control" type="date" name="start_date" id="start-date">
                  <div class="invalid-feedback"></div>
                </div>

                <div class="form-group">
                  <label for="due-date">Due Date <span class="asterisk">*</span></label>
                  <input class="form-control" type="date" name="due_date" id="due-date">
                  <div class="invalid-feedback"></div>
                </div>

                <div class="form-group mt-4">
                  <button id="submit-btn" class="btn btn-primary w-100" data-csrf-token="<?= e($_SESSION['csrf_token']) ?>" type="submit">Submit</button>
                </div>
              </form>
            </div>
          </div>
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
  <script src="../../assets/shared/js/create_donation.js"></script>
</body>

</html>