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
              <h1 class="m-0">Donations</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Donations</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid pb-1">
          <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
              <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary"><i class="fa-solid fa-heart"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Active</span>
                  <span id="active-count" class="info-box-number"></span>
                  <a class="stretched-link" href="./active_donations.php"></a>
                </div>
              </div>
            </div>

            <div class="col-md-3 col-sm-6 col-12">
              <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success"><i class="fa-solid fa-heart-circle-check"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Closed</span>
                  <span id="closed-count" class="info-box-number"></span>
                  <a class="stretched-link" href="./closed_donations.php"></a>
                </div>
              </div>
            </div>

            <?php if ($_SESSION['role_id'] === 2 || $_SESSION['role_id'] === 3) : ?>
              <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow-sm">
                  <span class="info-box-icon bg-secondary"><i class="fa-solid fa-heart-circle-plus"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">Create</span>
                    <a class="stretched-link" href="./create_donation.php"></a>
                  </div>
                </div>
              </div>

              <?php if ($_SESSION['role_id'] === 2) : ?>
                <div class="col-md-3 col-sm-6 col-12">
                  <div class="info-box shadow-sm">
                    <span class="info-box-icon bg-warning"><i class="fa-solid fa-heart-circle-exclamation"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">Requests</span>
                      <a class="stretched-link" href="./donation_requests.php"></a>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            <?php endif; ?>
          </div>

          <div class="card shadow-sm">
            <div class="card-header border-0">
              <h4 class="">My Donations</h4>
            </div>
            <div class="card-body py-0">
              <div class="table-responsive">
                <table class="table table-striped table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th data-type="string">Purpose</th>
                      <th data-type="number">Amount</th>
                      <th data-type="date">Start Date</th>
                      <th data-type="date">Due Date</th>
                      <th data-type="string">Status</th>
                      <th data-type="">Action</th>
                    </tr>
                  </thead>
                  <tbody id="table-body">
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
      <strong>&copy; 2025 <span class="text-warning">STMCP</span>.</strong> All rights reserved.
    </footer>
  </div>
  <!-- ./wrapper -->

  <?php require_once __DIR__ . '/../../includes/admin/scripts.php'; ?>
  <script src="../../assets/shared/js/donations.js"></script>
</body>

</html>