<?php require_once __DIR__ . '/../../includes/admin_auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once __DIR__ . '/../../includes/admin/head.php'; ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
  <div class="wrapper">

    <?php require_once __DIR__ . '/../../includes/admin/aside.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div id="contacts-content-wrapper" class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h1 class="m-0">Contacts</h1>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          <div class="row card overflow-hidden">
            <div class="card-header border-bottom-0 px-1">
              <div class="search-container col-12">
                <input id="search-contacts" class="search-input form-control" type="search" placeholder="Search contacts">
                <i id="search-icon" class="fa-solid fa-magnifying-glass"></i>
              </div>
            </div> <!-- /.card-header -->

            <div id="contacts-body" class="card-body px-2 py-2">
              <!-- Contacts goes here -->
            </div> <!-- /#contacts-body -->
          </div> <!-- /.row .card -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content -->

      <button id="add-contact" class="btn btn-warning rounded-circle" type="button" data-toggle="modal" data-target="#add-contact-modal" title="Add a contact">
        <i class="fa-solid fa-plus"></i>
      </button>

      <div id="add-contact-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modal-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <h5 id="modal-label" class="modal-title">Add New Contact</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body pb-0">
              <div class="card border-0 shadow-none">
                <div class="card-header p-2">
                  <div class="search-container col-12">
                    <input id="search-new-contacts" class="search-input form-control" type="search" placeholder="Search new contact">
                    <i id="search-new-icon" class="fa-solid fa-magnifying-glass"></i>
                  </div>
                </div>

                <div id="new-contact-results" class="card-body">
                  <!-- result goes here -->
                </div>
              </div> <!-- /.card -->
            </div>
          </div>
        </div>
      </div> <!-- /#add-contact-modal -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
      <strong>&copy; 2025 <span class="text-warning">STMCP</span>. All rights reserved.
    </footer>
  </div>
  <!-- ./wrapper -->

  <?php require_once __DIR__ . '/../../includes/admin/scripts.php'; ?>
  <script>
    const CURRENT_USER_ID = <?= e((int) $_SESSION['userID']) ?>;
  </script>
  <script src="../../assets/shared/js/contacts.js"></script>
</body>

</html>