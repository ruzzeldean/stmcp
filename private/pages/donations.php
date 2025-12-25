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

    <main id="donations-main-con" class="app-main" data-active-page="donations">
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="mb-0">Donations</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item active" aria-current="page">Donations</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <div class="app-content">
        <div class="container-fluid">
          <div class="row row-gap-3">
            <div class="col-lg-8">
              <div class="row">
                <div class="col-lg-6 col-6">
                  <div class="small-box text-bg-success">
                    <div class="inner">
                      <h3 id="active-count"></h3>

                      <p>Active Donations</p>
                    </div>
                    <i class="small-box-icon fa-solid fa-heart"></i>
                    <a
                      href="active_donations.php"
                      class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                      More info <i class="fa-solid fa-link"></i>
                    </a>
                  </div>
                </div>

                <div class="col-lg-6 col-6">
                  <div class="small-box text-bg-secondary">
                    <div class="inner">
                      <h3 id="closed-count"></h3>

                      <p>Closed Donations</p>
                    </div>
                    <i class="small-box-icon fa-solid fa-heart-circle-check"></i>
                    <a
                      href="closed_donations.php"
                      class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                      More info <i class="fa-solid fa-link"></i>
                    </a>
                  </div>
                </div>
              </div>

              <div class="card border-0 shadow-sm">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-borderless table-striped table-hover text-nowrap">
                      <thead>
                        <tr>
                          <th>Purpose</th>
                          <th>Amount</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="table-body"></tbody>
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

            <div class="col-lg-4">
              <?php if ($superAdmin || $admin) : ?>
                <div class="small-box text-bg-info mb-3">
                  <div class="inner">
                    <h3 id="pending-count"></h3>

                    <p>Pending Requests</p>
                  </div>
                  <i class="small-box-icon fa-solid fa-heart-circle-exclamation"></i>
                  <a href="pending_donations.php"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    More info <i class="fa-solid fa-link"></i>
                  </a>
                </div>
              <?php endif; ?>

              <div class="card border shadow-sm">
                <div class="card-body">
                  <img src="../assets/images/logo/one_star_logo.png" alt="" class="img-fluid">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php if ($superAdmin || $admin || $moderator) : ?>
        <a id="create-donation-btn" class="btn btn-primary rounded-circle" href="create_donation.php">
          <i class="fa-solid fa-heart-circle-plus"></i>
        </a>
      <?php endif; ?>
    </main>

    <?php include __DIR__ . '/../partials/footer.php'; ?>
  </div>

  <?php include __DIR__ . '/../partials/scripts.php'; ?>
  <script src="../assets/js/donations/donations.js"></script>
</body>

</html>