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
  echo e('Database error');
  exit;
}

$firstName = $row['first_name'];
$lastName = $row['last_name'];
$middleName = $row['middle_name'] ?? '';
$chapter = $row['chapter_id'];
$dateOfBirth = $row['date_of_birth'];
$civilStatus = $row['civil_status'];
$bloodType = $row['blood_type'];
$homeAddress = $row['home_address'];
$phoneNumber = $row['phone_number'];
$email = $row['email'];
$emergencyContactName = $row['emergency_contact_name'];
$emergencyContactNumber = $row['emergency_contact_number'];
$occupation = $row['occupation'];
$licenseNumber = $row['license_number'];
$motorcycleBrand = $row['motorcycle_brand'];
$motorcycleModel = $row['motorcycle_model'];
$sponsor = $row['sponsor'] ?? '';
$otherClubAffiliation = $row['other_club_affiliation'] ?? '';
$dateJoined = $row['date_joined'];
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
          <div class="xxl card shadow bg-body-tertiary px-1 py-2">
            <div class="card-body">
              <form id="membership-form" novalidate>
                <fieldset class="mb-4">
                  <legend>Personal Information</legend>
                  
                  <div class="row row-gap-3">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-label" for="first-name">First Name <span class="asterisk">*</span></label>
                        <input id="first-name" name="first_name" class="form-control" type="text" placeholder="Enter first name" value="<?= e($firstName) ?>">
                        <div class="invalid-feedback"></div>
                      </div> <!-- .form-group -->
                    </div> <!-- .col -->

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-label" for="last-name">Last Name <span class="asterisk">*</span></label>
                        <input id="last-name" name="last_name" class="form-control" type="text" placeholder="Enter last name" value="<?= e($lastName) ?>">
                        <div class="invalid-feedback"></div>
                      </div> <!-- .form-group -->
                    </div> <!-- .col -->

                    <div class="col-lg-3">
                      <div class="form-group">
                        <label class="form-label" for="middle-name">Middle Name</label>
                        <input id="middle-name" name="middle_name" class="form-control" type="text" placeholder="Enter middle name" value="<?= e($middleName) ?>">
                      </div> <!-- .form-group -->
                    </div> <!-- .col -->

                    <div class="col-lg-3">
                      <div class="form-group">
                        <label class="form-label" for="date-of-birth">Date of Birth <span class="asterisk">*</span></label>
                        <input id="date-of-birth" name="date_of_birth" class="form-control" type="date" value="<?= e($dateOfBirth) ?>">
                        <div class="invalid-feedback"></div>
                      </div> <!-- .form-group -->
                    </div> <!-- .col -->

                    <div class="col-lg-3">
                      <div class="form-group">
                        <label class="form-label" for="civil-status">Civil Status <span class="asterisk">*</span></label>
                        <select id="civil-status" name="civil_status" class="custom-select">
                          <option value="" selected disabled>Select Civil Status</option>
                          <option value="Single" <?= e($civilStatus === 'Single' ? 'selected' : '') ?>>Single</option>
                          <option value="Married" <?= e($civilStatus === 'Married' ? 'selected' : '') ?>>Married</option>
                          <option value="widowed" <?= e($civilStatus === 'Widowed' ? 'selected' : '') ?>>Widowed</option>
                          <option value="Separated" <?= e($civilStatus === 'Separated' ? 'selected' : '') ?>>Separated</option>
                        </select>
                        <div class="invalid-feedback"></div>
                      </div> <!-- .form-group -->
                    </div> <!-- .col -->

                    <div class="col-lg-3">
                      <div class="form-group">
                        <label class="form-label" for="blood-type">Blood Type <span class="asterisk">*</span></label>
                        <select id="blood-type" name="blood_type" class="custom-select">
                          <option value="" disabled selected>Select blood type</option>
                          <option value="A+" <?= e($bloodType === 'A+' ? 'selected' : '') ?>>A+</option>
                          <option value="A-" <?= e($bloodType === 'A-' ? 'selected' : '') ?>>A-</option>
                          <option value="B+" <?= e($bloodType === 'B+' ? 'selected' : '') ?>>B+</option>
                          <option value="B-" <?= e($bloodType === 'B-' ? 'selected' : '') ?>>B-</option>
                          <option value="AB+" <?= e($bloodType === 'AB+' ? 'selected' : '') ?>>AB+</option>
                          <option value="AB-" <?= e($bloodType === 'AB-' ? 'selected' : '') ?>>AB-</option>
                          <option value="O+" <?= e($bloodType === 'O+' ? 'selected' : '') ?>>O+</option>
                          <option value="O-" <?= e($bloodType === 'O-' ? 'selected' : '') ?>>O-</option>
                          <option value="Unknown" <?= e($bloodType === 'Unknown' ? 'selected' : '') ?>>Unknown</option>
                        </select>
                        <div class="invalid-feedback"></div>
                      </div> <!-- .form-group -->
                    </div> <!-- .col -->

                    <div class="col-lg-12">
                      <div class="form-group">
                        <label class="form-label" for="home-address">Home Address <span class="asterisk">*</span></label>
                        <input id="home-address" name="home_address" class="form-control" type="text" placeholder="Enter complete home address" value="<?= e($homeAddress) ?>">
                        <div class="invalid-feedback"></div>
                      </div> <!-- .form-group -->
                    </div> <!-- .col -->

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-label" for="phone-number">Phone Number <span class="asterisk">*</span></label>
                        <input id="phone-number" name="phone_number" class="form-control" type="tel" placeholder="Enter phone number" value="<?= e($phoneNumber) ?>">
                        <div class="invalid-feedback"></div>
                      </div> <!-- .form-group -->
                    </div> <!-- .col -->

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-label" for="email">Email Address <span class="asterisk">*</span></label>
                        <input id="email" name="email" class="form-control" type="email" placeholder="Enter email address" value="<?= e($email) ?>">
                        <div class="invalid-feedback"></div>
                      </div> <!-- .form-group -->
                    </div> <!-- .col -->
                  </div> <!-- .row -->
                </fieldset> <!-- fieldset -->

                <fieldset class="mb-4">
                  <legend>Emergency Contact</legend>

                  <div class="row row-gap-3">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-label" for="emergency-contact-name">Emergency Contact Name <span class="asterisk">*</span></label>
                        <input id="emergency-contact-name" name="emergency_contact_name" class="form-control" type="text" placeholder="Enter emergency contact name" value="<?= e($emergencyContactName) ?>">
                        <div class="invalid-feedback"></div>
                      </div> <!-- .form-group -->
                    </div> <!-- .col -->

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-label" for="emergency-contact-number">Emergency Contact Number <span class="asterisk">*</span></label>
                        <input id="emergency-contact-number" name="emergency_contact_number" class="form-control" type="tel" placeholder="Enter emergency contact number" value="<?= e($emergencyContactNumber) ?>">
                        <div class="invalid-feedback"></div>
                      </div> <!-- .form-group -->
                    </div> <!-- .col -->
                  </div> <!-- .row -->
                </fieldset> <!-- fieldset -->

                <fieldset class="mb-4">
                  <legend>Professional Information</legend>

                  <div class="row row-gap-3">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-label" for="occupation">Occupation <span class="asterisk">*</span></label>
                        <input id="occupation" name="occupation" class="form-control" type="text" placeholder="Enter occupation" value="<?= e($occupation) ?>">
                        <div class="invalid-feedback"></div>
                      </div> <!-- .form-group -->
                    </div> <!-- .col -->

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-label" for="license-number">Driver's License Number <span class="asterisk">*</span></label>
                        <input id="license-number" name="license_number" class="form-control" type="text" placeholder="Enter driver's license number" value="<?= e($licenseNumber) ?>">
                        <div class="invalid-feedback"></div>
                      </div> <!-- .form-group -->
                    </div> <!-- .col -->
                  </div> <!-- .row -->
                </fieldset> <!-- fieldset -->

                <fieldset class="mb-4">
                  <legend>Motorcycle Details</legend>

                  <div class="row row-gap-3">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-label" for="motorcycle-brand">Motorcycle Brand <span class="asterisk">*</span></label>
                        <input id="motorcycle-brand" name="motorcycle_brand" class="form-control" type="text" placeholder="Enter motorcycle brand" value="<?= e($motorcycleBrand) ?>">
                        <div class="invalid-feedback"></div>
                      </div> <!-- .form-group -->
                    </div> <!-- .col -->

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-label" for="motorcycle-model">Motorcycle Model <span class="asterisk">*</span></label>
                        <input id="motorcycle-model" name="motorcycle_model" class="form-control" type="text" placeholder="Enter motorcycle model" value="<?= e($motorcycleModel) ?>">
                        <div class="invalid-feedback"></div>
                      </div> <!-- .form-group -->
                    </div> <!-- .col -->
                  </div> <!-- .row -->
                </fieldset> <!-- fieldset -->

                <fieldset class="mb-4">
                  <legend>Club Membership Information</legend>

                  <div class="row row-gap-3">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-label" for="sponsor">I am being sponsored as an official/aspirant member of the Star Touring Motorcycle Club by Honorable Sir/Ma'am:</label>
                        <input id="sponsor" name="sponsor" class="form-control" type="text" placeholder="Enter sponsor" value="<?= e($sponsor) ?>">
                      </div> <!-- .form-group -->
                    </div> <!-- .col -->

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-label" for="other-club-affiliation">Are you affiliated with another motorcycle club? If yes, please indicate the name of the club and your role</label>
                        <input id="other-club-affiliation" name="other_club_affiliation" class="form-control" type="text" placeholder="Enter other club affiliations" value="<?= e($otherClubAffiliation) ?>">
                      </div> <!-- .form-group -->
                    </div> <!-- .col -->

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-label" for="chapter_id">Chapter <span class="asterisk">*</span></label>
                        <select id="chapter_id" name="chapter_id" class="custom-select">
                          <option value="" selected disabled>Select chapter near to you</option>
                          <option value="1" <?= e($chapter === 1 ? 'selected' : '') ?>>Cavite</option>
                          <option value="2" <?= e($chapter === 2 ? 'selected' : '') ?>>Ilocos (Sur, Norte)</option>
                          <option value="3" <?= e($chapter === 3 ? 'selected' : '') ?>>Laguna</option>
                          <option value="4" <?= e($chapter === 4 ? 'selected' : '') ?>>Malabon</option>
                          <option value="5" <?= e($chapter === 5 ? 'selected' : '') ?>>Mandaluyong & San Juan</option>
                          <option value="6" <?= e($chapter === 6 ? 'selected' : '') ?>>Manila</option>
                          <option value="7" <?= e($chapter === 7 ? 'selected' : '') ?>>Metro South (Taguig)</option>
                          <option value="8" <?= e($chapter === 8 ? 'selected' : '') ?>>Montalban (Rizal)</option>
                          <option value="9" <?= e($chapter === 9 ? 'selected' : '') ?>>North Caloocan</option>
                          <option value="10" <?= e($chapter === 10 ? 'selected' : '') ?>>Pampanga</option>
                          <option value="11" <?= e($chapter === 11 ? 'selected' : '') ?>>Pangasinan</option>
                          <option value="12" <?= e($chapter === 12 ? 'selected' : '') ?>>Pasay</option>
                          <option value="13" <?= e($chapter === 13 ? 'selected' : '') ?>>Pasig</option>
                          <option value="14" <?= e($chapter === 14 ? 'selected' : '') ?>>Quezon City</option>
                          <option value="15" <?= e($chapter === 15 ? 'selected' : '') ?>>Rizal</option>
                          <option value="16" <?= e($chapter === 16 ? 'selected' : '') ?>>San Jose Del Monte, Bulacan</option>
                          <option value="17" <?= e($chapter === 17 ? 'selected' : '') ?>>San Mateo (Rizal)</option>
                          <option value="18" <?= e($chapter === 18 ? 'selected' : '') ?>>Santa Maria, Bulacan</option>
                          <option value="19" <?= e($chapter === 19 ? 'selected' : '') ?>>Taguig & Makati</option>
                          <option value="20" <?= e($chapter === 20 ? 'selected' : '') ?>>Valenzuela</option>
                        </select>
                        <div class="invalid-feedback"></div>
                      </div> <!-- .form-group -->
                    </div> <!-- .col -->

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-label" for="date-joined">Date Joined the Club <span class="asterisk">*</span></label>
                        <input id="date-joined" name="date_joined" class="form-control" type="date" value="<?= e($dateJoined) ?>">
                        <div class="invalid-feedback"></div>
                      </div> <!-- .form-group -->
                    </div> <!-- .col -->
                  </div> <!-- .row -->
                </fieldset> <!-- fieldset -->
                
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">

                <input type="hidden" name="member_id" value="<?= e($memberID) ?>">

                <div class="row justify-content-center">
                  <div class="col-lg-6">
                    <button id="update-btn" class="btn btn-warning w-100" type="submit">Update</button>
                  </div>
                </div> <!-- .row -->
              </form>
            </div> <!-- .card-body -->
          </div> <!-- .card -->
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