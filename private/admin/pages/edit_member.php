<?php
require_once __DIR__ . '/../../includes/admin_auth_check.php';

if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT) || $_GET['id'] <= 0) {
  error_log('Invalid request: Missing Member ID');
  // header('Location: /stmcp/error_page.php?message=Invalid+Member+ID');
  exit;
}

$memberID = (int) $_GET['id'];

if (empty($_SESSION['csrfToken'])) {
  $_SESSION['csrfToken'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['csrfToken'];

try {
  $stmt = $conn->prepare('SELECT * FROM official_members WHERE member_id = :member_id');
  $stmt->execute(['member_id' => $memberID]);

  $row = $stmt->fetch();
  if (!$row) {
    error_log('No member found for ID: ' . $memberID);
    exit;
  }
} catch (Throwable $ex) {
  error_log('Error fetching member info: ' . $ex->getMessage());
  echo 'Database error';
  exit;
}

$firstName = $row['first_name'];
$middleName = $row['middle_name'] ?? '';
$lastName = $row['last_name'];
$chapter = $row['team_chapter'];
$dateOfBirth = $row['date_of_birth'];
$bloodType = $row['blood_type'];
$address = $row['address'];
$phoneNumber = $row['phone_number'];
$contactPersonNumber = $row['contact_person_number'];
$email = $row['email'];
$occupation = $row['occupation'];
$driversLicenseNumber = $row['drivers_license_number'];
$brand = $row['brand'];
$model = $row['model'];
$engineSizeCC = $row['engine_size_cc'];
$sponsoredBy = $row['sponsored_by'] ?? '';
$affiliations = $row['affiliations'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin | Star Touring Motorcycle Club</title>
  <link rel="shortcut icon" href="../../assets/shared/images/logo/logo.png" type="image/x-icon">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- AdminLTE 4 -->
  <link rel="stylesheet" href="../../assets/shared/css/adminlte.min.css">
  <!-- Custom css -->
  <link rel="stylesheet" href="../../assets/shared/css/style.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="./" class="brand-link">
        <img src="../../assets/shared/images/logo/logo.png" alt="STMCP Logo" class="brand-image">
        <span class="brand-text font-weight-light">ADMIN | STMCP</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="../../assets/shared/images/user-icon/user-icon.png" alt="User Icon">
          </div>
          <div class="info">
            <a href="#" class="d-block"><?php echo strtoupper($_SESSION['firstName']); ?></a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-header">MAIN</li>

            <li class="nav-item">
              <a href="./" class="nav-link">
                <i class="nav-icon fa-solid fa-gauge-high"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>

            <li class="nav-header">MEMBERS</li>

            <li class="nav-item">
              <a href="./official_members.php" class="nav-link active">
                <i class="nav-icon fa-solid fa-user-check"></i>
                <p>
                  Official Members
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="./user_accounts.php" class="nav-link">
                <i class="nav-icon fa-solid fa-users"></i>
                <p>User Accounts</p>
              </a>
            </li>

            <li class="nav-header">CONTENT MANAGEMENT</li>

            <li class="nav-item">
              <a href="./posts_management.php" class="nav-link">
                <i class="nav-icon fa-solid fa-newspaper"></i>
                <p>Posts Management</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="./donations.php" class="nav-link">
                <i class="nav-icon fa-solid fa-coins"></i>
                <p>Donations</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="./donations.php" class="nav-link">
                <i class="nav-icon fa-solid fa-tags"></i>
                <p>Merchandise</p>
              </a>
            </li>

            <li class="nav-header">SYSTEM</li>

            <li class="nav-item">
              <a href="./settings.php" class="nav-link">
                <i class="nav-icon fa-solid fa-gear"></i>
                <p>Settings</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="../../" class="nav-link">
                <i class="nav-icon fa-solid fa-arrow-right-from-bracket"></i>
                <p>Logout</p>
              </a>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Edit Member</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="./official_members.php">Official Members</a></li>
                <li class="breadcrumb-item active">Edit Member</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          <div id="update-member-form">
            <div class="card">
              <div class="card-body">
                <div class="form-group">
                  <label for="first-name">First Name <span class="text-danger">*</span></label>
                  <input type="text" id="first-name" class="form-control" placeholder="Enter first name" value ="<?php echo htmlspecialchars($firstName); ?>">
                </div>

                <div class="form-group">
                  <label for="middle-name">Middle Name</label>
                  <input type="text" id="middle-name" class="form-control" placeholder="Enter middle name" value ="<?php echo htmlspecialchars($middleName); ?>">
                </div>

                <div class="form-group">
                  <label for="last-name">Last Name</label>
                  <input type="text" id="last-name" class="form-control" placeholder="Enter last name" value ="<?php echo htmlspecialchars($lastName); ?>">
                </div>

                <div class="form-group">
                  <label for="chapter">Chapter</label>
                  <select id="chapter" class="custom-select">
                    <option value="" disabled selected>Select Chapter</option>
                    <option value="1" <?php echo htmlspecialchars($chapter == "1" ? "selected" : ""); ?>>Quezon City</option>
                    <option value="2" <?php echo htmlspecialchars($chapter == "2" ? "selected" : ""); ?>>Mandaluyong & San Juan</option>
                    <option value="3" <?php echo htmlspecialchars($chapter == "3" ? "selected" : ""); ?>>Pasay</option>
                    <option value="4" <?php echo htmlspecialchars($chapter == "4" ? "selected" : ""); ?>>Valuenzela</option>
                    <option value="5" <?php echo htmlspecialchars($chapter == "5" ? "selected" : ""); ?>>Manila</option>
                    <option value="6" <?php echo htmlspecialchars($chapter == "6" ? "selected" : ""); ?>>Taguig & Makati</option>
                    <option value="7" <?php echo htmlspecialchars($chapter == "7" ? "selected" : ""); ?>>San Jose Del Monte, Bulacan</option>
                    <option value="8" <?php echo htmlspecialchars($chapter == "8" ? "selected" : ""); ?>>Pampanga</option>
                    <option value="9" <?php echo htmlspecialchars($chapter == "9" ? "selected" : ""); ?>>Pasig</option>
                    <option value="10" <?php echo htmlspecialchars($chapter == "10" ? "selected" : ""); ?>>Santa Maria, Bulacan</option>
                    <option value="11" <?php echo htmlspecialchars($chapter == "11" ? "selected" : ""); ?>>Taytay, Cainta, Antipolo, Angono</option>
                    <option value="12" <?php echo htmlspecialchars($chapter == "12" ? "selected" : ""); ?>>North Caloocan</option>
                    <option value="13" <?php echo htmlspecialchars($chapter == "13" ? "selected" : ""); ?>>Metro South (Taguig)</option>
                    <option value="14" <?php echo htmlspecialchars($chapter == "14" ? "selected" : ""); ?>>Laguna</option>
                    <option value="15" <?php echo htmlspecialchars($chapter == "15" ? "selected" : ""); ?>>Cavite</option>
                    <option value="16" <?php echo htmlspecialchars($chapter == "16" ? "selected" : ""); ?>>Pangasinan</option>
                    <option value="17"<?php echo htmlspecialchars($chapter == "17" ? "selected" : ""); ?>>Ilocos (Sur, Norte)</option>
                    <option value="18" <?php echo htmlspecialchars($chapter == "18" ? "selected" : ""); ?>>Malabon</option>
                    <option value="19" <?php echo htmlspecialchars($chapter == "19" ? "selected" : ""); ?>>Montalban (Rizal)</option>
                    <option value="20" <?php echo htmlspecialchars($chapter == "20" ? "selected" : ""); ?>>San Mateo (Rizal)</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="date-of-birth">Date of Birth</label>
                  <input type="date" id="date-of-birth" class="form-control" value ="<?php echo htmlspecialchars($dateOfBirth); ?>">
                </div>

                <div class="form-group">
                  <label for="blood-type">Blood Type</label>
                  <select id="blood-type" class="custom-select">
                    <option value="" disabled selected>Select Blood Type</option>
                    <option value="A+" <?php echo htmlspecialchars($bloodType === "A+" ? "selected" : ""); ?>>A+</option>
                    <option value="A-" <?php echo htmlspecialchars($bloodType === "A-" ? "selected" : ""); ?>>A-</option>
                    <option value="B+" <?php echo htmlspecialchars($bloodType === "B+" ? "selected" : ""); ?>>B+</option>
                    <option value="B-" <?php echo htmlspecialchars($bloodType === "B-" ? "selected" : ""); ?>>B-</option>
                    <option value="AB+" <?php echo htmlspecialchars($bloodType === "AB+" ? "selected" : ""); ?>>AB+</option>
                    <option value="AB-" <?php echo htmlspecialchars($bloodType === "AB-" ? "selected" : ""); ?>>AB-</option>
                    <option value="O+" <?php echo htmlspecialchars($bloodType === "O+" ? "selected" : ""); ?>>O+</option>
                    <option value="O-" <?php echo htmlspecialchars($bloodType === "O-" ? "selected" : ""); ?>>O-</option>
                    <option value="Unknown" <?php echo htmlspecialchars($bloodType === "Unknown" ? "selected" : ""); ?>>Unknown</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="address">Address</label>
                  <input type="text" id="address" class="form-control" placeholder="Enter complete address" value ="<?php echo htmlspecialchars($address); ?>">
                </div>

                <div class="form-group">
                  <label for="phone-number">Phone Number</label>
                  <input type="tel" id="phone-number" class="phone form-control" placeholder="Personal #: 0912 345 6789" value ="<?php echo htmlspecialchars($phoneNumber); ?>">
                </div>

                <div class="form-group">
                  <label for="contact-person-number">Emergency Contact</label>
                  <input type="tel" id="contact-person-number" class="phone form-control" placeholder="Emergency #: 0998 767 4321" value ="<?php echo htmlspecialchars($contactPersonNumber); ?>">
                </div>

                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" id="email" class="form-control" placeholder="Enter email" value ="<?php echo htmlspecialchars($email); ?>">
                </div>

                <div class="form-group">
                  <label for="occupation">Occupation</label>
                  <input type="text" id="occupation" class="form-control" placeholder="Enter occupation" value ="<?php echo htmlspecialchars($occupation); ?>">
                </div>

                <div class="form-group">
                  <label for="drivers-license-number">Driver's Licence Number</label>
                  <input type="text" id="drivers-license-number" class="form-control" placeholder="Enter driver's licence number" value ="<?php echo htmlspecialchars($driversLicenseNumber); ?>">
                </div>

                <div class="form-group">
                  <label for="brand">Motorcycle Brand</label>
                  <input type="text" id="brand" class="form-control" placeholder="Enter motorcycle brand" value ="<?php echo htmlspecialchars($brand); ?>">
                </div>

                <div class="form-group">
                  <label for="model">Motorcycle model</label>
                  <input type="text" id="model" class="form-control" placeholder="Enter motorcycle model" value ="<?php echo htmlspecialchars($model); ?>">
                </div>

                <div class="form-group">
                  <label for="engine-size-cc">Engine Size (cc)</label>
                  <input type="text" id="engine-size-cc" class="form-control" placeholder="Enter engince size (cc)" value ="<?php echo htmlspecialchars($engineSizeCC); ?>">
                </div>

                <div class="form-group">
                  <label for="sponsor">Sponsored By</label>
                  <input type="text" id="sponsor" class="form-control" placeholder="Enter sponsor" value ="<?php echo htmlspecialchars($sponsoredBy); ?>">
                </div>

                <div class="form-group">
                  <label for="affiliations">Other Club Affiliations</label>
                  <input type="text" id="affiliations" class="form-control" placeholder="Enter other club affiliations" value ="<?php echo htmlspecialchars($affiliations); ?>">
                  <small class="form-text text-muted">Other *motorcycle* club affiliations</small>
                </div>

                <div class="form-group">
                  <button id="update-btn" class="btn btn-primary w-100" data-admin-id="<?php echo $memberID; ?>" data-csrf-token="<?php echo htmlspecialchars($csrfToken); ?>">Update</button>
                </div>
              </div> <!-- /.card-body -->
            </div> <!-- /.card -->
          </div> <!-- /#update-member-form -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
      <strong>&copy; 2025 <span class="text-warning">STMCP</span>. All rights reserved.
    </footer>
  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->
  <!-- jQuery -->
  <script src="../../assets/shared/js/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="../../assets/shared/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../../assets/shared/js/adminlte.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Custom Script -->
  <script src="../../assets/admin/js/official_members/edit_member.js"></script>
</body>

</html>