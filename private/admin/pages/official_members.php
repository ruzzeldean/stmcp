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
                          $sql = 'SELECT
                                    p.*,
                                    om.person_id,
                                    c.chapter_name
                                  FROM people p
                                  INNER JOIN official_members om
                                    ON p.person_id = om.person_id
                                  LEFT JOIN chapters c
                                    ON p.chapter_id = c.chapter_id';
                          $members = $db->fetchAll($sql);

                          foreach ($members as $member) :
                        ?>
                            <tr>
                              <td></td>
                              <td><?= e($member['person_id']) ?></td>
                              <td><?= e($member['first_name']) ?></td>
                              <td><?= e($member['middle_name'] ?? '') ?></td>
                              <td><?= e($member['last_name']) ?></td>
                              <td><?= e($member['chapter_name']) ?></td>
                              <td><?= e($member['date_of_birth']) ?></td>
                              <td><?= e($member['civil_status']) ?></td>
                              <td><?= e($member['blood_type']) ?></td>
                              <td><?= e($member['home_address']) ?></td>
                              <td><?= e($member['phone_number']) ?></td>
                              <td><?= e($member['email']) ?></td>
                              <td><?= e($member['emergency_contact_name']) ?></td>
                              <td><?= e($member['emergency_contact_number']) ?></td>
                              <td><?= e($member['occupation']) ?></td>
                              <td><?= e($member['license_number']) ?></td>
                              <td><?= e($member['motorcycle_brand']) ?></td>
                              <td><?= e($member['motorcycle_model']) ?></td>
                              <td><?= e($member['sponsor']) ?></td>
                              <td><?= e($member['other_club_affiliation']) ?></td>
                              <td><?= e($member['date_joined']) ?></td>
                              <td><?= e($member['created_at']) ?></td>
                              <td><?= e($member['updated_at']) ?></td>
                              <td>
                                <a class="edit-btn btn btn-secondary"
                                  href="./edit_member.php?id=<?= e($member['person_id']) ?>">Edit</a>

                                <button class="reject-btn btn btn-danger disabled"
                                  data-member-id="<?= e($member['person_id']) ?>" data-csrf-token="<?= e($csrfToken) ?>">Delete</button>
                              </td>
                            </tr>
                        <?php
                          endforeach;
                        } catch (Throwable $e) {
                          error_log('Error in: ' . $e);
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