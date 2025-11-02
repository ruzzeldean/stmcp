$(function () {
  $('#update-btn').click(function (e) {
    e.preventDefault();
    let valid = true;

    const $btn = $(this);
    $btn.prop('disabled', true);

    function validateField(selector, condition, message) {
      const $field = $(selector);

      if (condition) {
        $field.addClass('is-invalid').removeClass('is-valid');
        $field.next('.invalid-feedback').text(message).show();
        valid = false;
      } else {
        $field.removeClass('is-invalid').addClass('is-valid');
        $field.next('.invalid-feedback').hide();
      }
    }

    validateField(
      '#first-name',
      $('#first-name').val().trim() === '',
      'First name is required'
    );

    validateField(
      '#last-name',
      $('#last-name').val().trim() === '',
      'Last name is required'
    );

    if ($('#middle-name').val().trim() !== '') {
      $('#middle-name').addClass('is-valid');
    } else {
      $('#middle-name').removeClass('is-valid');
    }

    validateField(
      '#date-of-birth',
      $('#date-of-birth').val().trim() === '',
      'Date of birth is required'
    );

    validateField(
      '#civil-status',
      $('#civil-status').val() === null,
      'Civil status is required'
    );

    validateField(
      '#blood-type',
      $('#blood-type').val() === null,
      'Blood type is required'
    );

    validateField(
      '#home-address',
      $('#home-address').val().trim() === '',
      'Home address is required'
    );

    validateField(
      '#phone-number',
      $('#phone-number').val().trim() === '',
      'Phone number is required'
    );

    validateField(
      '#email',
      $('#email').val().trim() === '',
      'Email is required'
    );

    validateField(
      '#emergency-contact-name',
      $('#emergency-contact-name').val().trim() === '',
      'Emergency contact name is required'
    );

    validateField(
      '#emergency-contact-number',
      $('#emergency-contact-number').val().trim() === '',
      'Emergency contact number is required'
    );

    validateField(
      '#occupation',
      $('#occupation').val().trim() === '',
      'Occupation is required'
    );

    validateField(
      '#license-number',
      $('#license-number').val().trim() === '',
      "Driver's license number is required"
    );

    validateField(
      '#motorcycle-brand',
      $('#motorcycle-brand').val().trim() === '',
      'Motorcycle brand is required'
    );

    validateField(
      '#motorcycle-model',
      $('#motorcycle-model').val().trim() === '',
      'Motorcycle model is required'
    );

    if ($('#sponsor').val().trim() !== '') {
      $('#sponsor').addClass('is-valid');
    } else {
      $('#sponsor').removeClass('is-valid');
    }

    if ($('#other-club-affiliation').val().trim() !== '') {
      $('#other-club-affiliation').addClass('is-valid');
    } else {
      $('#other-club-affiliation').removeClass('is-valid');
    }

    validateField(
      '#chapter_id',
      $('#chapter_id').val() === null,
      'Chapter is required'
    );

    validateField(
      '#date-joined',
      $('#date-joined').val().trim() === '',
      'Date joined is required'
    );

    function loading() {
      $('#page-overlay').removeClass('d-none');
      $('#membership-form')
        .find('input, select, button')
        .prop('disabled', true);
    }

    if (valid) {
      $.ajax({
        url: '../actions/official_members/update_member.php',
        method: 'POST',
        dataType: 'json',
        data: $('#membership-form').serialize(),
        success: (res) => {
          if (res.status === 'success') {
            loading();
            Swal.fire('Success', res.message, 'success').then(() => {
              location.reload();
            });
          } else {
            $btn.prop('disabled', false);
            Swal.fire('Error', res.message, 'error');
          }
        },
        error: () => {
          $btn.prop('disabled', false);
          Swal.fire('Oops!', 'Something went wrong', 'error');
        },
      });
    } else {
      $btn.prop('disabled', false);
      Swal.fire(
        '',
        'Please fill out all required fields correctly',
        'warning'
      );
    }
  });

  $('.form-control, .custom-select').on('input change', function () {
    $(this).removeClass('is-invalid');
  });
});
