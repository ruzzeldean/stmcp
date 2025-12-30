<?php
include __DIR__ . '/../includes/auth.php';

requireModerator();
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

    <main class="app-main" data-csrf-token="<?= $csrfToken ?>">
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="mb-0">Aspirants</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item active" aria-current="page">Aspirants</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <div class="app-content">
        <div class="container-fluid">
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <div class="table-responsive">
                <table class="data-table display table table-borderless table-striped table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Actions</th>
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
                    </tr>
                  </thead>
                  <tbody id="table-body">
                    <tr>
                      <td colspan="100%" class="text-muted">
                        Loading...
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="row row-gap-2 mt-0 mt-md-3">
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
    </main>

    <?php include __DIR__ . '/../partials/footer.php'; ?>
  </div>

  <?php include __DIR__ . '/../partials/scripts.php'; ?>
  <script src="../assets/js/aspirants/aspirants.js"></script>
</body>

</html>