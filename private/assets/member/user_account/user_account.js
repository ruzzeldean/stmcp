$('#update-btn').click(function (e) {
  e.preventDefault();
  let valid = true;

  const $btn = $(this);
  $btn.prop('disabled', true);

  const $newPassword = $('#new-password').val().trim();
  const $confirmPassword = $('#confirm-password').val().trim();

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
    '#username',
    $('#username').val().trim() === '',
    'Username is required'
  );

  if (!$newPassword !== '' || $confirmPassword !== '') {
    validateField(
      '#new-password',
      $newPassword !== $confirmPassword,
      'Passwords do not match'
    );

    validateField(
      '#confirm-password',
      $confirmPassword !== $newPassword,
      'Passwords do not match'
    );
  }

  if (valid) {
    $.ajax({
      url: '../actions/user_account/update_account.php',
      method: 'POST',
      dataType: 'json',
      data: $('#membership-form').serialize(),
      success: (res) => {
        if (res.status === 'success') {
          // loading();
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
    Swal.fire('', 'Please fill out all required fields correctly', 'warning');
  }
});

$('.form-control, .custom-select').on('input change', function () {
  $(this).removeClass('is-invalid');
});
