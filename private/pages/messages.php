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

    <main id="contacts-content-wrapper" class="app-main">
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="mb-0">Messages</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item active" aria-current="page">Messages</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <div class="app-content">
        <div class="container-fluid">
          <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-header border-0">
              <div class="search-container">
                <input id="search-contacts" class="search-input form-control" type="search" placeholder="Search contacts">
                <i id="search-icon" class="fa-solid fa-magnifying-glass"></i>
              </div>
            </div>

            <div id="contacts-body" class="card-body"></div>
          </div>
        </div>
      </div>

      <button id="add-contact" type="button" class="btn btn-primary rounded-circle" data-bs-toggle="modal" data-bs-target="#add-contact-modal">
        <i class="fa-solid fa-user-plus"></i>
      </button>
    </main>

    <div class="modal fade" id="add-contact-modal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Contact</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="card border-0 shadow-none">
              <div class="card-header p-2">
                <div class="search-container col-12">
                  <input id="search-new-contacts" class="search-input form-control" type="search" placeholder="Search new contact">
                  <i id="search-new-icon" class="fa-solid fa-magnifying-glass"></i>
                </div>
              </div>

              <div id="new-contact-results" class="card-body">
                <p class="text-center text-muted">Start typing to search...</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php include __DIR__ . '/../partials/footer.php'; ?>
  </div>

  <?php include __DIR__ . '/../partials/scripts.php'; ?>
  <script>
    const CURRENT_USER_ID = <?= e((int) $_SESSION['user_id']) ?>;
  </script>
  <script src="../assets/js/messages/messages.js"></script>
</body>

</html>