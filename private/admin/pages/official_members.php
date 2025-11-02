<?php
require_once __DIR__ . '/../../includes/admin_auth_check.php';

if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['csrf_token'];
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
                          $sql = 'SELECT official_members.*, chapters.chapter_name
                                  FROM official_members
                                  JOIN chapters ON official_members.chapter_id = chapters.chapter_id';
                          $rows = $db->fetchAll($sql);

                          foreach ($rows as $row) :
                        ?>
                            <tr>
                              <td></td>
                              <td><?= e($row['member_id']) ?></td>
                              <td><?= e($row['first_name']) ?></td>
                              <td><?= e($row['middle_name'] ?? '') ?></td>
                              <td><?= e($row['last_name']) ?></td>
                              <td><?= e($row['chapter_name']) ?></td>
                              <td><?= e($row['date_of_birth']) ?></td>
                              <td><?= e($row['civil_status']) ?></td>
                              <td><?= e($row['blood_type']) ?></td>
                              <td><?= e($row['home_address']) ?></td>
                              <td><?= e($row['phone_number']) ?></td>
                              <td><?= e($row['email']) ?></td>
                              <td><?= e($row['emergency_contact_name']) ?></td>
                              <td><?= e($row['emergency_contact_number']) ?></td>
                              <td><?= e($row['occupation']) ?></td>
                              <td><?= e($row['license_number']) ?></td>
                              <td><?= e($row['motorcycle_brand']) ?></td>
                              <td><?= e($row['motorcycle_model']) ?></td>
                              <td><?= e($row['sponsor']) ?></td>
                              <td><?= e($row['other_club_affiliation']) ?></td>
                              <td><?= e($row['date_joined']) ?></td>
                              <td><?= e($row['created_at']) ?></td>
                              <td><?= e($row['updated_at']) ?></td>
                              <td>
                                <a class="edit-btn btn btn-secondary"
                                  href="./edit_member.php?id=<?= e($row['member_id']) ?>">Edit</a>

                                <button class="reject-btn btn btn-danger"
                                  data-member-id="<?= e($row['member_id']) ?>" data-csrf-token="<?= e($csrfToken) ?>">Delete</button>
                              </td>
                            </tr>
                        <?php
                          endforeach;
                        } catch (Throwable $e) {
                          error_log('Error in: ' . $e->getMessage());
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