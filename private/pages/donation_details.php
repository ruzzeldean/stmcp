<?php
include __DIR__ . '/../includes/auth.php';

$userId = $_SESSION['user_id'];
$donationId = $_GET['id'] ?? null;

if (!filter_var($donationId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
  http_response_code(400);
  exit('Invalid request.');
}

try {
  $db = new Database();

  $sql = 'SELECT purpose, description, status, start_date, due_date
          FROM donations
          WHERE donations.donation_id = ?';
  $donation = $db->fetchOne($sql, [$donationId]);

  if (!$donation) {
    http_response_code(404);
    exit('No donation found');
  }

  $sql = 'SELECT 1 FROM donors WHERE donation_id = ? AND user_id = ? LIMIT 1';
  $alreadyDonated = $db->fetchOne($sql, [$donationId, $userId]);
} catch (Throwable $e) {
  error_log('Error fetching donation details: ' . $e);
  exit('An unexpected error occurred. Please try again later.');
}

$startDate = new DateTime($donation['start_date']);
$dueDate = new DateTime($donation['due_date']);

$donation['start_date'] = $startDate->format('M j, Y');
$donation['due_date'] = $dueDate->format('M j, Y');

$status = $donation['status'];

$statusClass = match ($status) {
  'Active' => 'success',
  'Closed' => 'secondary',
  'Pending' => 'info',
  'Rejected' => 'danger',
  default => 'secondary'
};
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

    <main class="app-main" data-active-page="donation-details" data-donation-id=<?= e($donationId) ?>>
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="mb-0">Donation Details</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="donations.php">Donations</a></li>
                <?php if (e($status) !== 'Pending' && e($status) !== 'Rejected') : ?>
                  <li class="breadcrumb-item">
                    <a href="<?= e($status) === 'Active' ? 'active' : 'closed' ?>_donations.php">
                      <?= e($status) === 'Active' ? 'Active' : 'Closed' ?>
                    </a>
                  </li>
                <?php elseif (e($status) !== 'Active' && e($status) !== 'Closed') : ?>
                  <li class="breadcrumb-item">
                    <a href="<?= e($status) === 'Pending' ? 'pending' : 'rejected' ?>_donations.php">
                      <?= e($status) === 'Pending' ? 'Pending' : 'Rejected' ?>
                    </a>
                  </li>
                <?php endif; ?>
                <li class="breadcrumb-item active" aria-current="page">Details</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <div class="app-content">
        <div class="container-fluid">
          <div class="row row-gap-3">
            <div class="col-lg-6">
              <div class="card border-0 shadow-sm">
                <div class="card-body">
                  <h4 class="mb-2"><strong>Purpose:</strong> <?= e($donation['purpose']) ?></h4>

                  <?php if (e($donation['description'])) : ?>
                    <h5 class="mb-0"><strong>Description:</strong></h5>
                    <div class="ws-pre-wrap"><?= e($donation['description']) ?></div>
                  <?php endif; ?>

                  <p class="mt-2 mb-0"><strong>Start Date:</strong> <?= e($donation['start_date']) ?></p>
                  <p class="mt-0"><strong>Due Date:</strong> <?= e($donation['due_date']) ?></p>

                  <p class="card-text">
                    <strong>Status:</strong>
                    <span class="badge rounded-pill text-bg-<?= e($statusClass) ?>"><?= e($status) ?></span>
                  </p>

                  <?php if (!$alreadyDonated && e($status) !== 'Closed' && e($status) !== 'Pending' && e($status) !== 'Rejected') : ?>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#donate-modal">
                      Donate
                    </button>
                  <?php elseif (e($status) === 'Pending' && ($superAdmin || $admin)) : ?>
                    <div id="action-con">
                      <button class="action-btn btn btn-success" data-action="approve">Approve</button>
                      <button class="action-btn btn btn-danger" data-action="reject">Reject</button>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="card border-0 shadow-sm">
                <div class="card-body">
                  <h5><b>Donors</b></h5>
                  <div class="table-responsive">
                    <table class="table table-borderless table-striped table-hover text-nowrap">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Amount</th>
                          <th>Image</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="table-body">
                        <tr>
                          <td class="text-center text-muted" colspan="100%">Loading...</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="row row-gap-2">
                    <div class="col-md-6 order-md-last">
                      <nav class="">
                        <ul id="pagination-controls"
                          class="pagination pagination-sm justify-content-center justify-content-md-end mb-0 mt-3 mt-lg-0"></ul>
                      </nav>
                    </div>

                    <div id="pagination-info"
                      class="col-md-6 d-flex align-items-center justify-content-center justify-content-md-start">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <?php include __DIR__ . '/../partials/footer.php'; ?>
  </div>

  <?php if (!$alreadyDonated) : ?>
    <form id="donate-form" novalidate>
      <div class="modal fade" id="donate-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Donate</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="amount" class="form-label">Amount <span class="asterisk">*</span></label>
                <input class="form-control" type="number" min="1" name="amount" id="amount" placeholder="Enter donation amount" required>
                <div class="invalid-feedback"></div>
              </div>

              <div>
                <label for="image" class="form-label">Image <span class="asterisk">*</span></label>
                <input class="form-control" type="file" name="image" id="image" accept="image/*" required>
                <div class="invalid-feedback"></div>

                <img id="donation-img-preview" src="" class="img-fluid rounded mt-3" alt="">
              </div>

              <input id="csrf-token" type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
            </div>
            <div class="modal-footer">
              <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button id="donate-btn" type="submit" class="btn btn-success">Donate</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  <?php endif; ?>

  <?php include __DIR__ . '/../partials/scripts.php'; ?>
  <script src="../assets/js/donations/donations.js"></script>
</body>

</html>