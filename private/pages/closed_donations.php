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

    <main class="app-main" data-active-page="closed-donations">
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="mb-0">Closed Donations</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="donations.php">Donations</a></li>
                <li class="breadcrumb-item active" aria-current="page">Closed</li>
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
                <table class="table table-borderless table-striped table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Purpose</th>
                      <th>Start Date</th>
                      <th>Due Date</th>
                      <th>Status</th>
                      <th>Created By</th>
                      <th>Approved By</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="table-body">
                    <tr>
                      <td colspan="7" class="text-center text-muted">
                        Loading...
                      </td>
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
    </main>

    <?php include __DIR__ . '/../partials/footer.php'; ?>
  </div>

  <?php include __DIR__ . '/../partials/scripts.php'; ?>
  <script src="../assets/js/donations/donations.js"></script>
</body>

</html>