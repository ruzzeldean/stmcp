<?php
require_once __DIR__ . '/../../includes/member_auth_check.php';

$personId = $_SESSION['user_id'];

$sql = 'SELECT p.*, c.chapter_name
        FROM people AS p
        LEFT JOIN chapters AS c
          ON p.chapter_id = c.chapter_id
        WHERE person_id = :person_id';
$person = $db->fetchOne($sql, ['person_id' => $personId]);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once __DIR__ . '/../../includes/member/head.php'; ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
  <div class="wrapper">

    <?php require_once __DIR__ . '/../../includes/member/aside.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">My Profile</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">My Profile</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid pb-3">
          <div class="row">
            <div class="col-lg-4">
              <div class="card border-0 overflow-hidden">
                <img src="https://i.pravatar.cc/?img=12" alt="" class="card-img-top">
                <div class="card-body">
                  <h4 class="mb-4 text-center"><b><?= e($person['last_name'] . ', ' . $person['first_name'] . ' ' . $person['middle_name']) ?></b></h4>

                  <fieldset>
                    <legend>Personal Information</legend>
                    <ul class="list-unstyled">
                      <li><b>Date of Birth:</b> 16 Nov 2025</li>
                      <li><b>Civil Status:</b> Single</li>
                      <li><b>Blood Type:</b> A+</li>
                      <li><b>Home Address:</b> 94-D Area 17 UP Diliman Quezon City</li>
                      <li><b>Phone Number:</b> 0965478320067</li>
                      <li><b>Email Address:</b> example@gmail.com</li>
                    </ul>
                  </fieldset>
                </div>
              </div>
            </div> <!-- /.col-lg-4 -->

            <div class="col-lg-8">
              <div class="row">
                <div class="col-lg-6">
                  <div class="row">
                    <div class="col-12">
                      <div class="card">
                        <div class="card-header bg-danger"><span class="card-title">Emergency Contact</span></div>
                        <div class="card-body">
                          <ul class="list-unstyled">
                            <li><b>Contact Person:</b> <?= e($person['emergency_contact_name']) ?></li>
                            <li><b>Contact Number:</b> <?= e($person['emergency_contact_number']) ?></li>
                          </ul>
                        </div>
                      </div>
                    </div>

                    <div class="col-12">
                      <div class="card">
                        <div class="card-header bg-info"><span class="card-title">Professional Information</span></div>
                        <div class="card-body">
                          <ul class="list-unstyled">
                            <li><b>Occupation:</b> Sleeper</li>
                            <li><b>Driver's License no.:</b> DL-A0E-067</li>
                          </ul>
                        </div>
                      </div>
                    </div>

                    <div class="col-12">
                      <div class="card">
                        <div class="card-header bg-secondary"><span class="card-title">Motorcycle Details</span></div>
                        <div class="card-body">
                          <ul class="list-unstyled">
                            <li><b>Brand:</b> NMAX</li>
                            <li><b>Model:</b> V2</li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div> <!-- /.row -->
                </div> <!-- /.col-lg-6 -->

                <div class="col-lg-6">
                  <div class="row">
                    <div class="col-12">
                      <div class="card">
                        <div class="card-header bg-warning"><span class="card-title">Club Membership Information</span></div>
                        <div class="card-body">
                          <ul class="list-unstyled">
                            <li><b>Sponsored by:</b> <?= e($person['sponsor'] === '' ? 'None' : '') ?></li>
                            <li><b>Affiliatiated with other club:</b> <?= e($person['other_club_affiliation'] === '' ? 'No' : '') ?></li>
                            <li><b>Chapter:</b> <?= e($person['chapter_name']) ?></li>
                            <li><b>Date Joined:</b> <?= e($person['date_joined']) ?></li>
                          </ul>
                        </div>
                      </div>
                    </div>

                    <div class="col-12">
                      <a class="btn btn-primary w-100" href="./edit_profile.php?person_id=<?= e($personId) ?>">Update My Profile</a>
                    </div>
                  </div>
                </div> <!-- /.col-lg-6 -->
              </div> <!-- /.row -->
            </div> <!-- /.col-lg-8 -->
          </div> <!-- /.row -->
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

  <?php require_once __DIR__ . '/../../includes/member/scripts.php'; ?>
</body>

</html>