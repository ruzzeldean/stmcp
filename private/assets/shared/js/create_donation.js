$('#submit-btn').on('click', function (e) {
  e.preventDefault();

  const csrfToken = $(this).data('csrf-token');
  const $btn = $(this);
  $btn.prop('disabled', true);

  let valid = true;

  const purpose = $('#purpose').val().trim();
  const description = $('#description').val().trim();
  const startDate = $('#start-date').val();
  const dueDate = $('#due-date').val();

  validateField('#purpose', !purpose, 'Donation purpose is required');
  validateField('#start-date', !startDate, 'Start date is required');
  validateField('#due-date', !dueDate, 'Due date is required');

  if (!valid) {
    $btn.prop('disabled', false);
    Swal.fire({
      text: 'Please fill out all required fields correctly',
      icon: 'warning',
      allowOutsideClick: false,
      allowEscapeKey: false,
    });
    return;
  }

  $.ajax({
    url: '../../api/donations/create_donation.php',
    method: 'POST',
    dataType: 'json',
    data: {
      csrf_token: csrfToken,
      purpose: purpose,
      description: description,
      start_date: startDate,
      due_date: dueDate,
    },
    success: (res) => {
      if (res.status === 'success') {
        Swal.fire({
          text: res.message,
          icon: 'success',
          allowOutsideClick: false,
          allowEscapeKey: false,
        }).then(() => {
          location.reload();
        });
      } else {
        $btn.prop('disabled', false);
        Swal.fire({
          text: res.message,
          icon: 'error',
          allowOutsideClick: false,
          allowEscapeKey: false,
        });
      }
    },
    error: () => {
      $btn.prop('disabled', false);
      Swal.fire('', 'An error has occured. Please try again later', 'error');
    },
  });

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
});

$('.form-control').on('input change', function () {
  $(this).removeClass('is-invalid');
  $(this).closest('#create-donation-form').find('.invalid-feedback').hide();
});
