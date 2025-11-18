<?php
require_once __DIR__ . '/../../includes/member_auth_check.php';

$userId = $_GET['user_id'] ?? null;

if (!filter_var($userId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
  error_log('Invalid request: Missing or invalid Member ID');
  http_response_code(400);
  exit('Invalid request.');
}

if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['csrf_token'];

try {
  $sql = 'SELECT
            u.user_id,
            u.username
          FROM users AS u
          WHERE u.user_id = :user_id';
  $user = $db->fetchOne($sql, ['user_id' => $userId]);

  if (!$user) {
    error_log('No user found for ID: ' . $userId);
    http_response_code(400);
    exit('User not found.');
  }
} catch (Throwable $e) {
  error_log('Error fetching user info: ' . $e);
  exit('An unexpected error occurred. Please try again later.');
}
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
              <h1 class="m-0">Edit Account</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="./my_account.php">My Account</a></li>
                <li class="breadcrumb-item active">Edit</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid pb-3">
          <div id="edit-user-acc" class="card shadow bg-body-tertiary px-1 py-2">
            <div class="card-body">
              <form id="membership-form" novalidate>
                <row class="row row-gap-3">
                  <div class="col-12">
                    <div class="form-group">
                      <label class="form-label" for="username">Username <span class="asterisk">*</span></label>
                      <input id="username" name="username" class="form-control" type="text" placeholder="Enter username" value="<?= e($user['username']) ?>">
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="form-group">
                      <label class="form-label" for="new-password">New Password</label>
                      <input id="new-password" name="new_password" class="form-control" type="password" placeholder="Enter new password">
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="form-group">
                      <label class="form-label" for="confirm-password">Confirm Password</label>
                      <input id="confirm-password" name="confirm_password" class="form-control" type="password" placeholder="Confirm password">
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                  <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">

                  <input type="hidden" name="user_id" value="<?= e($userId) ?>">

                  <div class="col-12 mt-3">
                    <button id="update-btn" class="btn btn-warning w-100" type="submit">Update</button>
                  </div>
                </row>
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

  <?php require_once __DIR__ . '/../../includes/member/scripts.php'; ?>
  <script src="../../assets/member/user_account/user_account.js"></script>
</body>

</html>