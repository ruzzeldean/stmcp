<?php include '../../config/connection.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Star Touring Motorcycle Club Philippines</title>
  <link rel="shortcut icon" href="../../assets/img/logo/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="../../assets/icons/fontawesome/css/all.min.css">
  <!-- Fonts -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
    integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" />
  <!-- End of Fonts -->

  <!-- Plugin(OverlayScrollbars) -->
  <link rel="stylesheet" href="../../assets/css/overlayscrollbars.min.css" />
  <!-- End of Plugin(OverlayScrollbars) -->

  <!--begin::Third Party Plugin(Bootstrap Icons)-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI=" crossorigin="anonymous" />
  <!--end::Third Party Plugin(Bootstrap Icons)-->

  <!-- Required Plugin(AdminLTE) -->
  <link rel="stylesheet" href="../../assets/css/adminlte.min.css" />
  <!-- Plugin(AdminLTE) -->

  <!-- DataTables -->
  <link rel="stylesheet" href="../../assets/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="../../assets/css/responsive.bootstrap5.min.css">

  <!-- Custom css -->
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <!--begin::App Wrapper-->
  <div class="app-wrapper">
    <!--begin::Header-->
    <nav class="app-header navbar navbar-expand bg-body">
      <!--begin::Container-->
      <div class="container-fluid">
        <!-- Side bar toggler -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
              <i class="bi bi-list"></i>
            </a>
          </li>
        </ul>
        <!-- End of side bar toggler -->

        <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto">
          <!-- Fullscreen Toggler -->
          <li class="nav-item">
            <a class="nav-link" href="#" data-lte-toggle="fullscreen">
              <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
              <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
            </a>
          </li>
          <!-- End of Fullscreen Toggler-->

          <!--begin::User Menu Dropdown-->
          <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
              <img src="../../../AdminLTE-4.0.0-beta3/dist/assets/img/user2-160x160.jpg"
                class="user-image rounded-circle shadow" alt="User Image" />
              <span class="d-none d-md-inline">Alexander Pierce</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
              <!-- User image -->
              <li class="user-header text-bg-primary">
                <img src="../../../AdminLTE-4.0.0-beta3/dist/assets/img/user2-160x160.jpg" class="rounded-circle shadow"
                  alt="User Image" />
                <p>
                  Alexander Pierce - Web Developer
                  <small>Member since Nov. 2023</small>
                </p>
              </li>
              <!-- End of user image -->

              <!-- User Menu Body -->
              <li class="user-body">
                <!--begin::Row-->
                <div class="row">
                  <div class="col-4 text-center"><a href="#">Followers</a></div>
                  <div class="col-4 text-center"><a href="#">Sales</a></div>
                  <div class="col-4 text-center"><a href="#">Friends</a></div>
                </div>
                <!--end::Row-->
              </li>
              <!-- End of User Menu Body -->

              <!-- User Menu Footer -->
              <li class="user-footer">
                <a href="#" class="btn btn-default btn-flat">Profile</a>
                <a href="#" class="btn btn-default btn-flat float-end">Sign out</a>
              </li>
              <!-- End of User Menu Footer -->
            </ul>
          </li>
          <!--end::User Menu Dropdown-->
        </ul>
        <!--end::End Navbar Links-->
      </div>
      <!--end::Container-->
    </nav>
    <!--end::Header-->

    <!-- Sidebar -->
    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
      <!-- Sidebar Brand -->
      <div class="sidebar-brand">
        <!-- Brand Link -->
        <a href="../dashboard/dashboard.php" class="brand-link">
          <!-- Brand Image -->
          <img src="../../assets/img/logo/logo.png" alt="STMCP Logo" class="brand-image" />
          <!-- End of Brand Image -->

          <!-- Brand Text -->
          <span class="brand-text fw-light">STMCP | ADMIN</span>
          <!-- End of Brand Text -->
        </a>
        <!-- End of Brand Link -->
      </div>
      <!-- End of Sidebar Brand -->

      <!-- Sidebar Wrapper -->
      <div class="sidebar-wrapper">
        <nav class="mt-2">
          <!-- Sidebar Menu -->
          <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="../dashboard/dashboard.php" class="nav-link">
                <i class="nav-icon fa-solid fa-gauge"></i>
                <p>Dashboard</p>
              </a>
            </li>

            <li class="nav-header">Members</li>

            <li class="nav-item">
              <a href="./aspirants.php" class="nav-link active">
                <i class="nav-icon fa-solid fa-user-clock"></i>
                <p>Aspirants</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="../official-members/official-members.php" class="nav-link">
                <i class="nav-icon fa-solid fa-user-check"></i>
                <p>Official Members</p>
              </a>
            </li>

            <li class="nav-header">Post & Announcements</li>

            <li class="nav-item">
              <a href="../posts/posts.php" class="nav-link">
                <i class="nav-icon fa-solid fa-edit"></i>
                <p>Posts</p>
              </a>
            </li>

            <hr class="text-white my-2">

            <li class="nav-item">
              <a href="../../" class="nav-link">
                <i class="nav-icon fa-solid fa-arrow-right-from-bracket"></i>
                <p>Logout</p>
              </a>
            </li>
          </ul>
          <!-- End of Sidebar Menu -->
        </nav>
      </div>
      <!-- End of Sidebar Wrapper -->
    </aside>
    <!-- End of Sidebar -->

    <!-- App Main -->
    <main class="app-main">
      <!-- App Content Header -->
      <div class="app-content-header">
        <!-- Container -->
        <div class="container-fluid">
          <!-- Row -->
          <div class="row">
            <div class="col-sm-6">
              <h3 class="mb-0">Aspirants</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Aspirants</li>
              </ol>
            </div>
          </div>
          <!-- End of Row -->
        </div>
        <!-- End of Container -->
      </div>
      <!-- End of App Content Header -->

      <!-- App Content -->
      <div class="app-content">
        <!-- Container -->
        <div class="container-fluid">
          <div class="row">
            <div class="table-responsive py-2">
              <table id="example" class="table table-striped table-hover nowrap" style="width:100%">
                <thead>
                  <tr>
                    <th>Aspirant ID</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Date of Birth</th>
                    <th>Blood Type</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Address</th>
                    <th>Occupation</th>
                    <th>Emergenct Contact #</th>
                    <th>Driver License #</th>
                    <th>Motorcycle Make</th>
                    <th>Model</th>
                    <th>Chapter ID</th>
                    <th>Sponsored</th>
                    <th>Affiliations w other club(s)</th>
                    <th>Membership Status</th>
                    <th>Application Date</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $qry = mysqli_query($conn, "SELECT * FROM aspirants");

                  while ($row = mysqli_fetch_array($qry)) {
                  ?>
                    <tr>
                      <td><?php echo $row['aspirant_id']; ?></td>
                      <td><?php echo $row['first_name']; ?></td>
                      <td><?php echo $row['middle_name']; ?></td>
                      <td><?php echo $row['last_name']; ?></td>
                      <td><?php echo $row['date_of_birth']; ?></td>
                      <td><?php echo $row['blood_type']; ?></td>
                      <td><?php echo $row['email']; ?></td>
                      <td><?php echo $row['contact_number']; ?></td>
                      <td><?php echo $row['address']; ?></td>
                      <td><?php echo $row['occupation']; ?></td>
                      <td><?php echo $row['emergency_contact_no']; ?></td>
                      <td><?php echo $row['driver_license_no']; ?></td>
                      <td><?php echo $row['motorcycle_make']; ?></td>
                      <td><?php echo $row['model']; ?></td>
                      <td><?php echo $row['chapter_id']; ?></td>
                      <td><?php echo $row['sponsored']; ?></td>
                      <td><?php echo $row['affiliations_w_other_club']; ?></td>
                      <td><?php echo $row['membership_status']; ?></td>
                      <td><?php echo $row['application_date']; ?></td>
                      <td><?php echo $row['updated_at']; ?></td>
                      <td>
                        <button class="btn btn-success">Approve</button>
                        <button class="btn btn-danger">Reject</button>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!--end::Container-->
      </div>
      <!--end::App Content-->
    </main>
    <!--end::App Main-->
    <!--begin::Footer-->
    <footer class="app-footer">
      <!--begin::To the end-->
      <div class="float-end d-none d-sm-inline">Version 4.0.4</div>
      <!--end::To the end-->
      <!--begin::Copyright-->
      <strong>
        Copyright &copy; 2025&nbsp;
        <a href="https://stmcp.org/" class="text-decoration-none">STMCP</a>.
      </strong>
      All rights reserved.
      <!--end::Copyright-->
    </footer>
    <!--end::Footer-->
  </div>
  <!--end::App Wrapper-->

  <!-- Scripts -->
  <!-- jQuery -->
  <script src="../../assets/js/jquery-3.7.1.min.js"></script>
  <script src="../../assets/js/jquery.dataTables.min.js"></script>

  <!-- Plugin(OverlayScrollbars) -->
  <script src="../../assets/js/overlayscrollbars.browser.es6.min.js"></script>
  <!-- End of Plugin(OverlayScrollbars) -->

  <!-- Plugin(popperjs for Bootstrap 5) -->
  <script src="../../assets/js/popper.min.js"></script>
  <!-- Plugin(popperjs for Bootstrap 5) -->

  <!-- Plugin(Bootstrap 5) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
  </script>
  <!--end::Required Plugin(Bootstrap 5)-->

  <!--begin::Required Plugin(AdminLTE)-->
  <script src="../../assets/js/adminlte.min.js"></script>
  <!--end::Required Plugin(AdminLTE)-->

  <script src="../../assets/js/dataTables.bootstrap5.min.js"></script>
  <script src="../../assets/js/dataTables.responsive.min.js"></script>
  <script src="../../assets/js/responsive.bootstrap5.min.js"></script>

  <script src="../../assets/js/overlay.scrollbars.configure.js"></script>

  <script>
    $("#example").DataTable({
      responsive: true,
    });
  </script>
  
  <!-- End of Scripts -->
</body>
<!--end::Body-->

</html>