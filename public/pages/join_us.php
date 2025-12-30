<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
  <?php include __DIR__ . '/../partials/head.php'; ?>
</head>

<body>
  <div class="wrapper">
    <?php include __DIR__ . '/../partials/header.php'; ?>

    <main class="py-5">
      <div class="container pt-5">
        <div class="card bg-body-tertiary border-0 shadow-sm mt-3 py-4">
          <div class="card-header bg-body-tertiary border-0">
            <h1>Membership Form</h1>
            <p>Please fill out the form completely and accurately. Fields marked with <i>asterisk</i> (<span class="asterisk">*</span>) are required.</p>
          </div>

          <div class="card-body">
            <form id="membership-form" novalidate>
              <fieldset class="mb-4">
                <legend>Personal Information</legend>

                <div class="row row-gap-3">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-label" for="first-name">First Name <span class="asterisk">*</span></label>
                      <input id="first-name" name="first_name" class="form-control" type="text" placeholder="Enter first name">
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-label" for="last-name">Last Name <span class="asterisk">*</span></label>
                      <input id="last-name" name="last_name" class="form-control" type="text" placeholder="Enter last name">
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                  <div class="col-lg-3">
                    <div class="form-group">
                      <label class="form-label" for="middle-name">Middle Name</label>
                      <input id="middle-name" name="middle_name" class="form-control" type="text" placeholder="Enter middle name">
                    </div>
                  </div>

                  <div class="col-lg-3">
                    <div class="form-group">
                      <label class="form-label" for="date-of-birth">Date of Birth <span class="asterisk">*</span></label>
                      <input id="date-of-birth" name="date_of_birth" class="form-control" type="date">
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                  <div class="col-lg-3">
                    <div class="form-group">
                      <label class="form-label" for="civil-status">Civil Status <span class="asterisk">*</span></label>
                      <select id="civil-status" name="civil_status" class="form-select">
                        <option value="" selected disabled>Select Civil Status</option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Widowed">Widowed</option>
                        <option value="Separated">Separated</option>
                      </select>
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                  <div class="col-lg-3">
                    <div class="form-group">
                      <label class="form-label" for="blood-type">Blood Type <span class="asterisk">*</span></label>
                      <select id="blood-type" name="blood_type" class="form-select">
                        <option value="" disabled selected>Select blood type</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="Unknown">Unknown</option>
                      </select>
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                  <div class="col-lg-12">
                    <div class="form-group">
                      <label class="form-label" for="home-address">Home Address <span class="asterisk">*</span></label>
                      <input id="home-address" name="home_address" class="form-control" type="text" placeholder="Enter complete home address">
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-label" for="phone-number">Phone Number <span class="asterisk">*</span></label>
                      <input id="phone-number" name="phone_number" class="form-control" type="tel" placeholder="Enter phone number">
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-label" for="email">Email Address <span class="asterisk">*</span></label>
                      <input id="email" name="email" class="form-control" type="email" placeholder="Enter email address">
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>


                </div>
              </fieldset>

              <fieldset class="mb-4">
                <legend>Emergency Contact</legend>

                <div class="row row-gap-3">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-label" for="emergency-contact-name">Emergency Contact Name <span class="asterisk">*</span></label>
                      <input id="emergency-contact-name" name="emergency_contact_name" class="form-control" type="text" placeholder="Enter emergency contact name">
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-label" for="emergency-contact-number">Emergency Contact Number <span class="asterisk">*</span></label>
                      <input id="emergency-contact-number" name="emergency_contact_number" class="form-control" type="tel" placeholder="Enter emergency contact number">
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>
                </div>
              </fieldset>

              <fieldset class="mb-4">
                <legend>Professional Information</legend>

                <div class="row row-gap-3">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-label" for="occupation">Occupation <span class="asterisk">*</span></label>
                      <input id="occupation" name="occupation" class="form-control" type="text" placeholder="Enter occupation">
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-label" for="license-number">Driver's License Number <span class="asterisk">*</span></label>
                      <input id="license-number" name="license_number" class="form-control" type="text" placeholder="Enter driver's license number">
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>
                </div>
              </fieldset>

              <fieldset class="mb-4">
                <legend>Motorcycle Details</legend>

                <div class="row row-gap-3">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-label" for="motorcycle-brand">Motorcycle Brand <span class="asterisk">*</span></label>
                      <input id="motorcycle-brand" name="motorcycle_brand" class="form-control" type="text" placeholder="Enter motorcycle brand">
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-label" for="motorcycle-model">Motorcycle Model <span class="asterisk">*</span></label>
                      <input id="motorcycle-model" name="motorcycle_model" class="form-control" type="text" placeholder="Enter motorcycle model">
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>
                </div>
              </fieldset>

              <fieldset class="mb-4">
                <legend>Club Membership Information</legend>

                <div class="row row-gap-3">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-label" for="sponsor">I am being sponsored as an official/aspirant member of the Star Touring Motorcycle Club by Honorable Sir/Ma'am:</label>
                      <input id="sponsor" name="sponsor" class="form-control" type="text" placeholder="Enter sponsor">
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-label" for="other-club-affiliation">Are you affiliated with another motorcycle club? If yes, please indicate the name of the club and your role</label>
                      <input id="other-club-affiliation" name="other_club_affiliation" class="form-control" type="text" placeholder="Enter other club affiliations">
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-label" for="chapter-id">Chapter <span class="asterisk">*</span></label>
                      <select id="chapter-id" name="chapter_id" class="form-select">
                        <option value="" selected disabled>Select chapter near to you</option>
                        <option value="1">Cavite</option>
                        <option value="2">Ilocos (Sur, Norte)</option>
                        <option value="3">Laguna</option>
                        <option value="4">Malabon</option>
                        <option value="5">Mandaluyong & San Juan</option>
                        <option value="6">Manila</option>
                        <option value="7">Metro South (Taguig)</option>
                        <option value="8">Montalban (Rizal)</option>
                        <option value="9">North Caloocan</option>
                        <option value="10">Pampanga</option>
                        <option value="11">Pangasinan</option>
                        <option value="12">Pasay</option>
                        <option value="13">Pasig</option>
                        <option value="14">Quezon City</option>
                        <option value="15">Rizal</option>
                        <option value="16">San Jose Del Monte, Bulacan</option>
                        <option value="17">San Mateo (Rizal)</option>
                        <option value="18">Santa Maria, Bulacan</option>
                        <option value="19">Taguig & Makati</option>
                        <option value="20">Valenzuela</option>
                      </select>
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-label" for="date-joined">Date Joined the Club <span class="asterisk">*</span></label>
                      <input id="date-joined" name="date_joined" class="form-control" type="date">
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>
                </div>
              </fieldset>

              <div class="row row-gap-3 mb-5">
                <div class="col-12">
                  <div class="form-check">
                    <input id="terms-privacy-consent" name="terms_privacy_consent" class="form-check-input custom-checkbox" type="checkbox" value="1">
                    <label class="form-check-label" for="terms-privacy-consent">By checking this box, I confirm that I have read and agree to the <a class="text-warning" href=""><strong>Terms of Service</strong></a>. and the <a class="text-warning" href=""><strong>Privacy Policy</strong></a> of the Star Touring Motorcycle Club Philippines. I understand that my personal information will be used in accordance with these documents. <span class="asterisk">*</span></label>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-check">
                    <input id="liability-waiver" name="liability_waiver" class="form-check-input custom-checkbox" type="checkbox" value="1">
                    <label class="form-check-label" for="liability-waiver">I acknowledge the risks involved in participating in the club's events and agree to waiver liability for any injuries or damages that may occur. <a class="text-warning" href="#" data-bs-toggle="modal" data-bs-target="#liability-waiver-modal"><strong>Read the full waiver</strong></a>. <span class="asterisk">*</span></label>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>
              </div>

              <input type="hidden" name="type" value="membership">

              <div class="row justify-content-center">
                <div class="col-lg-6">
                  <button id="submit-btn" class="btn btn-warning w-100" type="submit">Submit</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </main>

    <?php include __DIR__ . '/../partials/footer.php'; ?>
  </div>

  <div class="modal fade" id="liability-waiver-modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="modalLabel">Waiver and Liability Release</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="mb-4">This waiver is provided for informational purposes. All members and participants are encouraged to read and understand the following terms before joining any club activity.</p>

          <h4 class="my-4">WAIVER AND LIABILITY TERMS</h4>

          <p>
            Participation in any activity organized by the Star Touring Motorcycle
            Club Philippines (the Club) is voluntary and may involve certain risks,
            including but not limited to accidents, personal injury, or property
            damage.
          </p>

          <p>
            The participant acknowledges full responsibility for their own safety,
            actions, and equipment during rides and events.
          </p>

          <p>
            The Club and its organizers shall not be held liable for any injuries,
            damages, or losses incurred during participation in official or unofficial
            club activities.
          </p>

          <p>
            All riders are expected to follow traffic rules and safety protocols,
            including wearing proper safety gear and ensuring their motorcycles are in
            roadworthy condition.
          </p>

          <p>
            This waiver remains in effect throughout the participant's involvement
            with the Club and applies to all current and future events, unless
            otherwise revoked in writing.
          </p>

          <img src="../../src/images/logo/one_star_logo.png" class="img-fluid" alt="One Star Logo">

          <em class="mt-3 d-block">
            <small class="text-muted">
              This is a digital copy for viewing only. For signed acknowledgment,
              please coordinate directly with the Club's administrator.
            </small>
          </em>
        </div>
        <div class="modal-footer d-flex">
          <a href="../downloads/liability_waiver.docx"
          class="btn btn-primary" download>Download as DOCX</a>

          <a href="../downloads/liability_waiver.pdf"
          class="btn btn-danger" download>Download as PDF</a>
        </div>
      </div>
    </div>
  </div>

  <div id="page-overlay" class="d-none text-warning">
    <div class="spinner-border" role="status" style="width: 3rem; height: 3rem;">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>

  <?php include __DIR__ . '/../partials/scripts.php'; ?>
  <script src="../assets/js/join_us/join_us.js"></script>
</body>

</html>