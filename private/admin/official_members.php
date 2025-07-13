<?php
require_once '../includes/admin_auth_check.php';

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
  <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.4/css/responsive.bootstrap4.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.3/css/buttons.bootstrap4.css">
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
              <a href="./official_members.php" class="nav-link active">
                <i class="nav-icon fa-solid fa-user-check"></i>
                <p>
                  Official Members
                </p>
              </a>
            </li>

            <li class="nav-header">CLUB CONTENTS</li>

            <li class="nav-item">
              <a href="./posts_management.php" class="nav-link">
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
              <h1 class="m-0">Official Members</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Official Members</li>
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
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="data-table table table-striped table-hover text-nowrap my-3">
                      <thead>
                        <tr>
                          <th></th>
                          <th>ID </th>
                          <th>First Name </th>
                          <th>Middle Name </th>
                          <th>Last Name </th>
                          <th>Chapter </th>
                          <th>Date of Birth </th>
                          <th>Blood Type </th>
                          <th>Address </th>
                          <th>Phone Number </th>
                          <th>Emergency Contact </th>
                          <th>Email </th>
                          <th>Occupation </th>
                          <th>Driver's License Number </th>
                          <th>Motorcycle Brand </th>
                          <th>Motorcycle Model </th>
                          <th>Engine Size (cc) </th>
                          <th>Sponsored By </th>
                          <th>Affiliations </th>
                          <th>Created At </th>
                          <th>Updated At </th>
                          <th>Actions </th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php

                        try {
                          $qry = $conn->prepare(
                            'SELECT official_members.*, chapters.chapter_name
                            FROM official_members
                            JOIN chapters ON official_members.team_chapter = chapters.chapter_id'
                          );
                          $qry->execute();

                          while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                            <tr>
                              <td></td>
                              <td><?php echo htmlspecialchars($row['member_id']); ?></td>
                              <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                              <td><?php echo htmlspecialchars($row['middle_name'] ?? ''); ?></td>
                              <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                              <td><?php echo htmlspecialchars($row['chapter_name']); ?></td>
                              <td><?php echo htmlspecialchars($row['date_of_birth']); ?></td>
                              <td><?php echo htmlspecialchars($row['blood_type']); ?></td>
                              <td><?php echo htmlspecialchars($row['address']); ?></td>
                              <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                              <td><?php echo htmlspecialchars($row['contact_person_number']); ?></td>
                              <td><?php echo htmlspecialchars($row['email']); ?></td>
                              <td><?php echo htmlspecialchars($row['occupation']); ?></td>
                              <td><?php echo htmlspecialchars($row['drivers_license_number']); ?></td>
                              <td><?php echo htmlspecialchars($row['brand']); ?></td>
                              <td><?php echo htmlspecialchars($row['model']); ?></td>
                              <td><?php echo htmlspecialchars($row['engine_size_cc']); ?></td>
                              <td><?php echo htmlspecialchars($row['sponsored_by']); ?></td>
                              <td><?php echo htmlspecialchars($row['affiliations']); ?></td>

                              <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                              <td><?php echo htmlspecialchars($row['updated_at']); ?></td>
                              <td>
                                <a class="edit-btn btn btn-secondary"
                                  href="./edit_member.php?id=<?php echo htmlspecialchars($row['member_id']); ?>">Edit</a>

                                <button class="reject-btn btn btn-danger"
                                  data-member-id="<?php echo htmlspecialchars($row['member_id']); ?>" data-csrf-token="<?php echo htmlspecialchars($csrfToken); ?>">Delete</button>
                              </td>
                            </tr>
                        <?php
                          }
                        } catch (Throwable $ex) {
                          error_log('Error in: ' . $ex->getMessage());
                          echo '<div class="alert alert-warning" role="alert">An error occured while fetching data. Please try again later.</div>';
                        }
                        ?>
                      </tbody>
                    </table>
                  </div> <!-- /.table-responsive -->
                </div> <!-- /.card-body -->
              </div> <!-- /.card -->
            </div> <!-- /.col-12 -->
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
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- jQuery -->
  <script src="../assets/js/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../assets/js/adminlte.min.js"></script>
  <!-- DataTables Core -->
  <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap4.js"></script>
  <!-- DataTables Responsive -->
  <script src="https://cdn.datatables.net/responsive/3.0.4/js/dataTables.responsive.js"></script>
  <script src="https://cdn.datatables.net/responsive/3.0.4/js/responsive.bootstrap4.js"></script>
  <!-- DataTables Buttons -->
  <script src="https://cdn.datatables.net/buttons/3.2.3/js/dataTables.buttons.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.2.3/js/buttons.bootstrap4.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.2.3/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.2.3/js/buttons.colVis.min.js"></script>
  <!-- Custom Script -->
  <script src="../assets/js/data-tables.js"></script>
  <script src="../assets/js/delete_member.js"></script>
</body>

</html>