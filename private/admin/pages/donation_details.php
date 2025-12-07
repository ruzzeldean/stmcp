<?php
require_once __DIR__ . '/../../includes/admin_auth_check.php';

if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$userId = $_SESSION['user_id'] ?? 0;
$donationId = $_GET['id'] ?? null;

if (!filter_var($donationId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
  error_log('Invalid request: Missing or invalid Donation ID');
  http_response_code(400);
  exit('Invalid request.');
}

try {
  $sql = 'SELECT
            purpose,
            description,
            status,
            start_date,
            due_date
          FROM donations
          WHERE donation_id = :donation_id
          LIMIT 1';
  $donation = $db->fetchOne($sql, ['donation_id' => $donationId]);

  if (!$donation) {
    exit('No donation found');
    # redirect to 404 error page
  }

  $sql = 'SELECT 1
          FROM donors
          WHERE donation_id = :donation_id
            AND user_id = :user_id
          LIMIT 1';
  $alreadyDonated = $db->fetchOne($sql, [
    'donation_id' => $donationId,
    'user_id' => $userId
  ]);
} catch (Throwable $e) {
  error_log("Error fetching donation details: $e");
  exit('An unexpect error occured. Please try again later');
}

$badge = match ($donation['status']) {
  'Active' => 'success',
  'Closed' => 'secondary',
  default => 'secondary'
};
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
              <h1 class="m-0">Donation Details</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="./donations.php">Donations</a></li>
                <li class="breadcrumb-item"><a href="./<?= e($donation['status'] === 'Active' ? 'active' : 'closed') ?>_donations.php"><?= e($donation['status'] === 'Active' ? 'Active' : 'Closed') ?></a></li>
                <li class="breadcrumb-item active">Details</li>
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
            <div class="col-lg-6">
              <div class="card shadow-sm">
                <div class="card-body">
                  <h4 class="card-text mb-3"><b>Purpose:</b> <?= e($donation['purpose']) ?></h4>
                  <?php if ($donation['description']) : ?>
                    <p class="card-text ws-pre-wrap"><b>Description: </b><br><?= e($donation['description']) ?></p>
                  <?php endif; ?>
                  <div class="mb-3">
                    <p class="card-text mb-0"><b>Start Date:</b> <?= e(date('F j, Y', strtotime($donation['start_date']))) ?></p>
                    <p class="card-text"><b>Due Date:</b> <?= e(date('F j, Y', strtotime($donation['due_date']))) ?></p>
                  </div>
                  <p class="card-text mb-4"><b>Status:</b> <span class="badge badge-pill bg-<?= e($badge) ?>"><?= e($donation['status']) ?></span></p>
                  <?php if (!$alreadyDonated && $donation['status'] !== 'Closed') : ?>
                    <div>
                      <button class="btn btn-primary" data-toggle="modal" data-target="#donate-modal" type="button">Donate</button>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="card shadow-sm">
                <div class="card-header border-0">
                  <h4 class="">Donors</h4>
                </div>
                <div class="card-body py-0">
                  <div class="table-responsive">
                    <table class="table table-striped table-hover text-nowrap">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Amount</th>
                          <th>Proof</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="table-body" data-donation-id="<?= e($donationId) ?>">
                        
                      </tbody>
                    </table>
                  </div>
                </div>
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

  <?php if (!$alreadyDonated) : ?>
    <!-- Modal -->
    <form id="donate-form">
      <div class="modal fade" id="donate-modal" tabindex="-1" aria-labelledby="modal-title" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modal-title">Donate</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label class="form-label" for="amount">Amount</label>
                <input class="form-control" type="number" name="amount" id="amount" min="1" step=".01" placeholder="Enter amount">
                <div class="invalid-feedback"></div>
              </div>

              <div class="form-group mb-3">
                <label class="form-label" for="proof-image">Upload</label>
                <input id="proof-image" class="form-control rounded p-1" type="file" name="proof_image" accept="image/png, image/jpg, image/jpeg">
                <div class="invalid-feedback"></div>
              </div>

              <input id="csrf-token" type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
              <input id="donation-id" type="hidden" name="donation_id" value="<?= e($donationId) ?>">
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
              <button id="save-btn" type="button" class="btn btn-primary">Save</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  <?php endif; ?>

  <?php require_once __DIR__ . '/../../includes/admin/scripts.php'; ?>
  <script src="../../assets/shared/js/donate.js"></script>
</body>

</html>