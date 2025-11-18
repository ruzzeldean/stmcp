<?php
require_once __DIR__ . '/../../includes/member_auth_check.php';

$userId = $_SESSION['user_id'];

$sql = 'SELECT
          u.username,
          u.role_id,
          u.status,
          r.role,
          p.first_name,
          p.last_name
        FROM users AS u
        LEFT JOIN roles AS r
          ON u.role_id = r.role_id
        LEFT JOIN people AS p
          ON u.person_id = p.person_id
        WHERE u.user_id = :user_id
        LIMIT 1';

$user = $db->fetchOne($sql, ['user_id' => $userId]);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once __DIR__ . '/../../includes/member/head.php'; ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
  <div class="wrapper">

    <?php require_once __DIR__ . '/../../includes/member/aside.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">My Account</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">My Account</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          <div class="px-1">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="data-table table table-striped table-hover text-nowrap my-3">
                        <thead>
                          <tr>
                            <th></th>
                            <th>User ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <td></td>
                          <td><?= e($userId) ?></td>
                          <td><?= e($user['first_name']) ?></td>
                          <td><?= e($user['last_name']) ?></td>
                          <td><?= e($user['username']) ?></td>
                          <td><?= e($user['role']) ?></td>
                          <td><?= e($user['status']) ?></td>
                          <td>
                            <a class="btn btn-info" href="./edit_account.php?user_id=<?= e($userId) ?>" title="Update">
                              <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                          </td>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.row -->
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

  <?php require_once __DIR__ . '/../../includes/member/scripts.php'; ?>
</body>

</html>