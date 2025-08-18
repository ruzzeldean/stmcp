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
                          <th>Civil Status </th>
                          <th>Blood Type </th>
                          <th>Home Address </th>
                          <th>Phone Number </th>
                          <th>Email</th>
                          <th>Emergency Contact Person </th>
                          <th>Emergency Contact Number </th>
                          <th>Occupation </th>
                          <th>License Number </th>
                          <th>Motorcycle Brand </th>
                          <th>Motorcycle Model </th>
                          <th>Sponsor </th>
                          <th>Other Club Affiliation </th>
                          <th>Date Joined </th>
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
                            JOIN chapters ON official_members.chapter_id = chapters.chapter_id'
                          );
                          $qry->execute();

                          while ($row = $qry->fetch()) {
                        ?>
                            <tr>
                              <td></td>
                              <td><?php echo e($row['member_id']); ?></td>
                              <td><?php echo e($row['first_name']); ?></td>
                              <td><?php echo e($row['middle_name'] ?? ''); ?></td>
                              <td><?php echo e($row['last_name']); ?></td>
                              <td><?php echo e($row['chapter_name']); ?></td>
                              <td><?php echo e($row['date_of_birth']); ?></td>
                              <td><?php echo e($row['civil_status']); ?></td>
                              <td><?php echo e($row['blood_type']); ?></td>
                              <td><?php echo e($row['home_address']); ?></td>
                              <td><?php echo e($row['phone_number']); ?></td>
                              <td><?php echo e($row['email']); ?></td>
                              <td><?php echo e($row['emergency_contact_name']); ?></td>
                              <td><?php echo e($row['emergency_contact_number']); ?></td>
                              <td><?php echo e($row['occupation']); ?></td>
                              <td><?php echo e($row['license_number']); ?></td>
                              <td><?php echo e($row['motorcycle_brand']); ?></td>
                              <td><?php echo e($row['motorcycle_model']); ?></td>
                              <td><?php echo e($row['sponsor']); ?></td>

                              <td><?php echo e($row['other_club_affiliation']); ?></td>
                              <td><?php echo e($row['date_joined']); ?></td>

                              <td><?php echo e($row['created_at']); ?></td>
                              <td><?php echo e($row['updated_at']); ?></td>
                              <td>
                                <a class="edit-btn btn btn-secondary"
                                  href="./edit_member.php?id=<?php echo e($row['member_id']); ?>">Edit</a>

                                <button class="reject-btn btn btn-danger"
                                  data-member-id="<?php echo e($row['member_id']); ?>" data-csrf-token="<?php echo e($csrfToken); ?>">Delete</button>
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
  
  <?php require_once __DIR__ . '/../../includes/admin/scripts.php'; ?>
  <script src="../../assets/admin/js/official_members/delete_member.js"></script>
</body>

</html>