<?php
include __DIR__ . '/../includes/auth.php';

requireAdmin();

$userId = $_GET['id'] ?? null;

if (!filter_var($userId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
  // error_log('Invalid request: Missing or invalid User ID');
  http_response_code(400);
  exit('Invalid request.');
}

try {
  $db = new Database();

  $sql = 'SELECT
            u.user_id,
            u.username,
            u.password,
            u.role_id,
            u.status
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

$role = $user['role_id'];
$status = $user['status'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include __DIR__ . '/../partials/head.php'; ?>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">
    <?php include __DIR__ . '/../partials/header.php'; ?>

    <?php include __DIR__ . '/../partials/aside.php'; ?>

    <main class="app-main">
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="mb-0">Update User Account</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="user_accounts.php">User Accounts</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <div class="app-content">
        <div class="container-fluid">
          <div class="card border-0 shadow-sm max-w-500">
            <div class="card-body">
              <form id="update-form" novalidate>
                <div class="row row-gap-3">
                  <input id="user-id" type="hidden" name="user_id" value="<?= e($user['user_id']) ?>">

                  <div class="col-12">
                    <div class="form-group">
                      <label class="form-label" for="username">Username <span class="asterisk">*</span></label>
                      <input id="username" name="username" class="form-control" type="text" placeholder="Enter username" value="<?= e($user['username']) ?>" autocomplete="username" required>
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="form-group">
                      <label class="form-label" for="new-password">Password</label>
                      <input id="new-password" name="new_password" class="form-control" type="password" placeholder="Enter new password" autocomplete="off">
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="form-group">
                      <label class="form-label" for="confirm-password">Confirm Password</label>
                      <input id="confirm-password" name="confirm_password" class="form-control" type="password" placeholder="Confirm password" autocomplete="off">
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="form-group">
                      <label class="form-label" for="role-id">Role <span class="asterisk">*</span></label>
                      <select class="form-select" name="role_id" id="role-id" required
                        <?= e($role === 5 ? 'disabled' : '') ?>>
                        <option value="" selected disabled>Select Role</option>
                        <?php if ($superAdmin) : ?>
                          <option value="1" <?= e($role === 1 ? 'selected' : '') ?>>Super Admin</option>
                        <?php endif; ?>
                        <option value="2" <?= e($role === 2 ? 'selected' : '') ?>>Admin</option>
                        <option value="3" <?= e($role === 3 ? 'selected' : '') ?>>Moderator</option>
                        <option value="4" <?= e($role === 4 ? 'selected' : '') ?>>Member</option>
                        <option value="5" <?= e($role === 5 ? 'selected' : '') ?>>Aspirant</option>
                      </select>
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="form-group">
                      <label class="form-label" for="status">Status <span class="asterisk">*</span></label>
                      <select class="form-select" name="status" id="status" required>
                        <option value="" selected disabled>Select Status</option>
                        <option value="Active" <?= e($status === 'Active' ? 'selected' : '') ?>>Active</option>
                        <option value="Disabled" <?= e($status === 'Disabled' ? 'selected' : '') ?>>Disable</option>
                      </select>
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                  <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>" required>

                  <input type="hidden" name="user_id" value="<?= e($userId) ?>" required>

                  <div class="col-12 mt-3">
                    <button id="update-btn" class="btn btn-primary w-100" type="submit">Update</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </main>

    <?php include __DIR__ . '/../partials/footer.php'; ?>
  </div>

  <?php include __DIR__ . '/../partials/scripts.php'; ?>
  <script src="../assets/js/user_accounts/update_user_account.js"></script>
</body>

</html>