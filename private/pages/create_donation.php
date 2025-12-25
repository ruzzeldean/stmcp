<?php include __DIR__ . '/../includes/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include __DIR__ . '/../partials/head.php'; ?>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">
    <?php include __DIR__ . '/../partials/header.php'; ?>

    <?php include __DIR__ . '/../partials/aside.php'; ?>

    <main class="app-main" data-active-page="create-update">
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="mb-0">Create Donation</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="donations.php">Donations</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <div class="app-content">
        <div class="container-fluid">
          <div class="card border-0 shadow-sm max-w-500">
            <div class="card-body">
              <form id="create-donation-form" data-form-type="create" novalidate>
                <div class="mb-3">
                  <label for="purpose" class="form-label">Purpose <span class="asterisk">*</span></label>
                  <input class="form-control" type="text" name="purpose" id="purpose" placeholder="Enter donation purpose" required>
                  <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                  <label for="description" class="form-label">Description <span class="asterisk">*</span></label>
                  <textarea name="description" id="description" rows="3" class="form-control" placeholder="Enter description" required></textarea>
                  <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                  <label for="start-date" class="form-label">Start Date <span class="asterisk">*</span></label>
                  <input type="date" name="start_date" id="start-date" class="form-control" required>
                  <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                  <label for="due-date" class="form-label">Due Date <span class="asterisk">*</span></label>
                  <input type="date" name="due_date" id="due-date" class="form-control" required>
                  <div class="invalid-feedback"></div>
                </div>

                <input id="csrf-token" type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                <input id="action" type="hidden" name="action" value="create">

                <div class="mt-4">
                  <button id="submit-btn" class="btn btn-primary w-100">Create</button>
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
  <script src="../assets/js/donations/donations.js"></script>
</body>

</html>