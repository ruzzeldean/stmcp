$(document).ready(function () {
  $('#submit-btn').click(function (e) {
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

    function validateCheckBox(selector, message) {
      const $checkbox = $(selector);

      if (!$checkbox.is(':checked')) {
        $checkbox
          .closest('.form-check')
          .find('.invalid-feedback')
          .text(message)
          .show();
        valid = false;
      } else {
        $checkbox.closest('.form-check').find('.invalid-feedback').hide();
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
    validateField(
      '#chapter-id',
      $('#chapter-id').val() === null,
      'Chapter is required'
    );
    validateField(
      '#date-joined',
      $('#date-joined').val().trim() === '',
      'Date joined is required'
    );

    validateCheckBox(
      '#terms-privacy-consent',
      'You must agree to the Terms and Privacy Policy'
    );
    validateCheckBox(
      '#liability-waiver',
      'You must accept the liability waiver'
    );

    function loading() {
      $('#page-overlay').removeClass('d-none');
      $('#membership-form')
        .find('input, select, button')
        .prop('disabled', true);
    }

    function displayToast(
      type,
      title,
      message,
      position = 'topCenter',
      timeout = 3000
    ) {
      iziToast[type]({
        title: title,
        message: message,
        position: position,
        timeout: timeout,
        onClosed: () => {
          if (type === 'success') {
            location.reload();
          }
        },
      });
    }

    if (valid) {
      $.ajax({
        url: './actions/membership/membership.php',
        method: 'POST',
        dataType: 'json',
        data: $('#membership-form').serialize(),
        success: (response) => {
          if (response.status === 'success') {
            loading();
            displayToast('success', 'Success', response.message);
          } else {
            $btn.prop('disabled', false);
            displayToast('error', 'Error', response.message);
          }
        },
        error: () => {
          $btn.prop('disabled', false);
          displayToast('error', 'Error', 'Oops, something went wrong');
        },
      });
    } else {
      $btn.prop('disabled', false);
      displayToast('warning', '', 'Please fill out all required fields correctly');
    }
  });

  $('.form-control, .form-select, .form-check-input').on(
    'input change',
    function () {
      $(this).removeClass('is-invalid');
      $(this).closest('.form-check').find('.invalid-feedback').hide();
    }
  );
});
