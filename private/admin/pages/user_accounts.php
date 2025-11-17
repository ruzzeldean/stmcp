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
              <h1 class="m-0">User Accounts</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">User Accounts</li>
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
                          <th>Created At</th>
                          <th>Updated At</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        try {
                          $sql = 'SELECT
                                    u.user_id,
                                    u.username,
                                    u.role_id,
                                    u.status,
                                    u.created_at,
                                    u.updated_at,
                                    p.first_name,
                                    p.last_name
                                  FROM users u
                                  LEFT JOIN people p
                                      ON u.person_id = p.person_id
                                  LEFT JOIN aspirants a
                                      ON u.person_id = a.person_id
                                  LEFT JOIN official_members om
                                      ON u.person_id = om.person_id
                                  WHERE u.role_id != "1"';

                          $users = $db->fetchAll($sql);

                          foreach ($users as $user) :
                            switch ($user['role_id']) {
                              case 1:
                                $roleName = 'Super Admin';
                                break;

                              case 2:
                                $roleName = 'Admin';
                                break;

                              case 3:
                                $roleName = 'Moderator';
                                break;

                              case 4:
                                $roleName = 'Member';
                                break;

                              case 5:
                                $roleName = 'Aspirant';
                                break;

                              default:
                                echo 'Invalid role assigned';
                            }

                            $creationDate = $user['created_at'];
                            $updateDate = $user['updated_at'];
                            $createdAt = date('d M Y', strtotime($creationDate));
                            $updatedAt = date('d M Y', strtotime($updateDate));
                        ?>
                            <tr>
                              <td></td>
                              <td><?= e($user['user_id']) ?></td>
                              <td><?= e($user['first_name']) ?></td>
                              <td><?= e($user['last_name']) ?></td>
                              <td><?= e($user['username']) ?></td>
                              <td><?= e($roleName) ?></td>
                              <td><?= e($user['status']) ?></td>
                              <td><?= e($createdAt) ?></td>
                              <td><?= e($updatedAt) ?></td>
                              <td>
                                <!-- <button class="btn btn-secondary"><i class="fa-solid fa-ban"></i></button> -->
                                <a class="btn btn-info" href="edit_user_account.php?user_id=<?= e($user['user_id']) ?>" title="Update Record"><i class="fa-solid fa-pen-to-square"></i></a>
                                <button class="btn btn-danger disabled" title="Delete Record"><i class="fa-solid fa-trash"></i></button>
                              </td>
                            </tr>
                        <?php
                          endforeach;
                        } catch (Throwable $e) {
                          error_log('Error fetching users: ' . $e);
                          echo 'Error fetching user accounts';
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
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