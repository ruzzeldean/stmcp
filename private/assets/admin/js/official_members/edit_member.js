$(function () {
  $('.phone').on('input', function () {
    let input = $(this).val().replace(/\D/g, '');
    if (input.length > 11) input = input.slice(0, 11);

    let formatted = input;

    if (input.length > 3 && input.length <= 6) {
      formatted = input.slice(0, 4) + ' ' + input.slice(4);
    } else if (input.length > 6) {
      formatted =
        input.slice(0, 4) + ' ' + input.slice(4, 7) + ' ' + input.slice(7);
    }

    $(this).val(formatted);
  });

  $('#update-btn').click(function () {
    const memberID = $(this).data('admin-id');
    const firstName = $('#first-name').val().trim();
    const middleName = $('#middle-name').val().trim();
    const lastName = $('#last-name').val().trim();
    const chapter = $('#chapter').val();
    const dateOfBirth = $('#date-of-birth').val();
    const bloodType = $('#blood-type').val();
    const address = $('#address').val().trim();
    const phoneNumber = $('#phone-number').val().trim();
    const contactPersonNumber = $('#contact-person-number').val();
    const email = $('#email').val().trim();
    const occupation = $('#occupation').val().trim();
    const driversLicenseNumber = $('#drivers-license-number').val().trim();
    const brand = $('#brand').val().trim();
    const model = $('#model').val().trim();
    const engineSizeCC = $('#engine-size-cc').val().trim();
    const sponsoredBy = $('#sponsor').val().trim();
    const affiliations = $('#affiliations').val().trim();
    const csrfToken = $(this).data('csrf-token');

    if (!firstName) {
      alert('First name is required.');
    } else if (!lastName) {
      alert('Last name is required.');
    } else if (!chapter) {
      alert('Chapter is required.');
    } else if (!dateOfBirth) {
      alert('Date is required.');
    } else if (!bloodType) {
      alert('Blood type is required');
    } else if (!address) {
      alert('Address is required.');
    } else if (!phoneNumber) {
      alert('Phone number is required.');
    } else if (!contactPersonNumber) {
      alert('Emergency contact is required.');
    } else if (!email) {
      alert('Email is required.');
    } else if (!occupation) {
      alert('Occupation is required.');
    } else if (!driversLicenseNumber) {
      alert("Driver's license is required.");
    } else if (!brand) {
      alert('Motorcycle brand is required.');
    } else if (!model) {
      alert('Motorcycle model is required.');
    } else if (!engineSizeCC) {
      alert('Engine size (cc) is required.');
    } else if (!sponsoredBy) {
      alert('Required field.');
    } else if (!affiliations) {
      alert('Required field.');
    } else {
      $.ajax({
        url: '../actions/official_members/update_member.php',
        method: 'POST',
        dataType: 'json',
        data: {
          memberID: memberID,
          firstName: firstName,
          middleName: middleName,
          lastName: lastName,
          dateOfBirth: dateOfBirth,
          bloodType: bloodType,
          address: address,
          phoneNumber: phoneNumber,
          contactPersonNumber: contactPersonNumber,
          email: email,
          occupation: occupation,
          driversLicenseNumber: driversLicenseNumber,
          brand: brand,
          model: model,
          engineSizeCC: engineSizeCC,
          sponsoredBy: sponsoredBy,
          affiliations: affiliations,
          chapter: chapter,
          csrfToken: csrfToken,
        },
        success: (response) => {
          if (response.status === 'success') {
            Swal.fire('Success', response.message, 'success').then(() => {
              location.reload();
            });
          } else {
            Swal.fire('Error', response.message, 'error');
          }
        },
        error: () => {
          Swal.fire('Oops!', 'Something went wrong', 'error');
        },
      });
    }
  });
});
