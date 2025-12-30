<?php
include __DIR__ . '/../includes/auth.php';

requireAdmin();

$personId = (int)$_GET['id'] ?? null;

if (!filter_var($personId, FILTER_VALIDATE_INT, [
  'options' => ['min_range' => 1]
])) {
  http_response_code(400);
  exit('Invalid request: Missing or invalid member ID');
}

try {
  $db = new Database();

  $sql = 'SELECT * FROM people
          WHERE person_id = :person_id
          ';
  $member = $db->fetchOne($sql, ['person_id' => $personId]);
} catch (Throwable $e) {
  error_log("Error fetching member: $e");
}

$civilStatus = $member['civil_status'];
$bloodType = $member['blood_type'];
$chapter = $member['chapter_id'];
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

    <main class="app-main" data-active-page="update-member">
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">

              <h3 class="mb-0">Update Member</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="official_members.php">Official Members</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <div class="app-content">
        <div class="container-fluid">
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <form id="update-member-form" novalidate>
                <fieldset class="mb-4 border-0">
                  <legend>Personal Information</legend>

                  <div class="row row-gap-3">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-label" for="first-name">First Name <span class="asterisk">*</span></label>
                        <input id="first-name" name="first_name" class="form-control" type="text" placeholder="Enter first name" value="<?= e($member['first_name']) ?>" autocomplete="first-name">
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-label" for="last-name">Last Name <span class="asterisk">*</span></label>
                        <input id="last-name" name="last_name" class="form-control" type="text" placeholder="Enter last name" value="<?= e($member['last_name']) ?>">
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <div class="col-lg-3">
                      <div class="form-group">
                        <label class="form-label" for="middle-name">Middle Name</label>
                        <input id="middle-name" name="middle_name" class="form-control" type="text" placeholder="Enter middle name" value="<?= e($member['middle_name'] ?? '') ?>">
                      </div>
                    </div>

                    <div class="col-lg-3">
                      <div class="form-group">
                        <label class="form-label" for="date-of-birth">Date of Birth <span class="asterisk">*</span></label>
                        <input id="date-of-birth" name="date_of_birth" class="form-control" type="date" value="<?= e($member['date_of_birth']) ?>">
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <div class="col-lg-3">
                      <div class="form-group">
                        <label class="form-label" for="civil-status">Civil Status <span class="asterisk">*</span></label>
                        <select id="civil-status" name="civil_status" class="form-select">
                          <option value="" selected disabled>Select Civil Status</option>
                          <option value="Single" <?= e($civilStatus === 'Single' ? 'selected' : '') ?>>Single</option>
                          <option value="Married" <?= e($civilStatus === 'Married' ? 'selected' : '') ?>>Married</option>
                          <option value="widowed" <?= e($civilStatus === 'Widowed' ? 'selected' : '') ?>>Widowed</option>
                          <option value="Separated" <?= e($civilStatus === 'Separated' ? 'selected' : '') ?>>Separated</option>
                        </select>
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <div class="col-lg-3">
                      <div class="form-group">
                        <label class="form-label" for="blood-type">Blood Type <span class="asterisk">*</span></label>
                        <select id="blood-type" name="blood_type" class="form-select">
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
                      </div>
                    </div>

                    <div class="col-lg-12">
                      <div class="form-group">
                        <label class="form-label" for="home-address">Home Address <span class="asterisk">*</span></label>
                        <input id="home-address" name="home_address" class="form-control" type="text" placeholder="Enter complete home address" value="<?= e($member['home_address']) ?>">
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-label" for="phone-number">Phone Number <span class="asterisk">*</span></label>
                        <input id="phone-number" name="phone_number" class="form-control" type="tel" placeholder="Enter phone number" value="<?= e($member['phone_number']) ?>">
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-label" for="email">Email Address <span class="asterisk">*</span></label>
                        <input id="email" name="email" class="form-control" type="email" placeholder="Enter email address" value="<?= e($member['email']) ?>" autocomplete="email">
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>
                  </div>
                </fieldset>

                <fieldset class="mb-4 border-0">
                  <legend>Emergency Contact</legend>

                  <div class="row row-gap-3">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-label" for="emergency-contact-name">Emergency Contact Name <span class="asterisk">*</span></label>
                        <input id="emergency-contact-name" name="emergency_contact_name" class="form-control" type="text" placeholder="Enter emergency contact name" value="<?= e($member['emergency_contact_name']) ?>">
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-label" for="emergency-contact-number">Emergency Contact Number <span class="asterisk">*</span></label>
                        <input id="emergency-contact-number" name="emergency_contact_number" class="form-control" type="tel" placeholder="Enter emergency contact number" value="<?= e($member['emergency_contact_number']) ?>">
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>
                  </div>
                </fieldset>

                <fieldset class="mb-4 border-0">
                  <legend>Professional Information</legend>

                  <div class="row row-gap-3">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-label" for="occupation">Occupation <span class="asterisk">*</span></label>
                        <input id="occupation" name="occupation" class="form-control" type="text" placeholder="Enter occupation" value="<?= e($member['occupation']) ?>">
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-label" for="license-number">Driver's License Number <span class="asterisk">*</span></label>
                        <input id="license-number" name="license_number" class="form-control" type="text" placeholder="Enter driver's license number" value="<?= e($member['license_number']) ?>">
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>
                  </div>
                </fieldset>

                <fieldset class="mb-4 border-0">
                  <legend>Motorcycle Details</legend>

                  <div class="row row-gap-3">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-label" for="motorcycle-brand">Motorcycle Brand <span class="asterisk">*</span></label>
                        <input id="motorcycle-brand" name="motorcycle_brand" class="form-control" type="text" placeholder="Enter motorcycle brand" value="<?= e($member['motorcycle_brand']) ?>">
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-label" for="motorcycle-model">Motorcycle Model <span class="asterisk">*</span></label>
                        <input id="motorcycle-model" name="motorcycle_model" class="form-control" type="text" placeholder="Enter motorcycle model" value="<?= e($member['motorcycle_model']) ?>">
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>
                  </div>
                </fieldset>

                <fieldset class="mb-4 border-0">
                  <legend>Club Membership Information</legend>

                  <div class="row row-gap-3">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-label" for="sponsor">I am being sponsored as an official/aspirant member of the Star Touring Motorcycle Club by Honorable Sir/Ma'am:</label>
                        <input id="sponsor" name="sponsor" class="form-control" type="text" placeholder="Enter sponsor" value="<?= e($member['sponsor'] ?? '') ?>">
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-label" for="other-club-affiliation">Are you affiliated with another motorcycle club? If yes, please indicate the name of the club and your role</label>
                        <input id="other-club-affiliation" name="other_club_affiliation" class="form-control" type="text" placeholder="Enter other club affiliations" value="<?= e($member['other_club_affiliation'] ?? '') ?>">
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-label" for="chapter-id">Chapter <span class="asterisk">*</span></label>
                        <select id="chapter-id" name="chapter_id" class="form-select">
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
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-label" for="date-joined">Date Joined the Club <span class="asterisk">*</span></label>
                        <input id="date-joined" name="date_joined" class="form-control" type="date" value="<?= e($member['date_joined']) ?>">
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>
                  </div>
                </fieldset>

                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">

                <input type="hidden" name="person_id" value="<?= e($member['person_id']) ?>">

                <input type="hidden" name="action" value="updateMember">

                <div class="row justify-content-center">
                  <div class="col-lg-6">
                    <button id="update-btn" class="btn btn-primary w-100" type="submit">Update</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </main>

    <?php include __DIR__ . '/../partials/footer.php'; ?>
  </div>

  <div id="page-overlay" class="d-none text-light">
    <div class="spinner-border" role="status" style="width: 3rem; height: 3rem;">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>

  <?php include __DIR__ . '/../partials/scripts.php'; ?>
  <script src="../assets/js/official_members/official_members.js"></script>
</body>

</html>