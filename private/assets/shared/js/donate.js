$(function () {
  const donationId = $('#table-body').data('donation-id');

  $.ajax({
    url: '../../api/donations/donors.php',
    method: 'GET',
    data: { donation_id: donationId },
    dataType: 'json',
    success: (res) => {
      if (res.status === 'success') {
        donors(res.data);
      } else {
        console.log(res.message);
      }
    },
    error: (err) => {
      console.error(err);
    },
  });

  function donors(donors) {
    const $body = $('#table-body');
    $body.empty();

    donors.forEach((donor) => {
      const html = `
        <tr>
          <td class="text-truncate max-w-250">${donor.donor_name}</td>
          <td>Php ${donor.amount}</td>
          <td style="max-width: 1rem;"><img src="../../uploads/receipts/${donor.proof_image}" class="img-thumbnail"></td>
          <td><button class="btn btn-danger" title="Delete"><i class="fa-solid fa-trash"></i></button>
        </tr>
      `;

      $body.append(html);
    });
  }

  $('#save-btn').on('click', function (e) {
    e.preventDefault();

    const $btn = $(this);
    $btn.prop('disabled', true);

    $('#amount, #proof-image')
      .removeClass('is-valid is-invalid')
      .next('.invalid-feedback')
      .hide();

    const amount = $('#amount').val().trim();
    const fileInput = $('#proof-image')[0];
    const imageFile = fileInput?.files[0];

    const MAX_SIZE = 5 * 1024 * 1024;
    let valid = true;

    if (amount === '' || isNaN(amount) || Number(amount) <= 0) {
      setFieldFeedback('#amount', false, 'Please enter a valid amount');
      valid = false;
    } else {
      setFieldFeedback('#amount', true);
    }

    if (!imageFile) {
      setFieldFeedback('#proof-image', false, 'Please upload an image');
      valid = false;
    } else {
      if (!imageFile.type.startsWith('image/')) {
        setFieldFeedback('#proof-image', false, 'Only image files are allowed');
        valid = false;
      } else if (imageFile.size > MAX_SIZE) {
        setFieldFeedback(
          '#proof-image',
          false,
          'Image must be smaller than 5 MB'
        );
        valid = false;
      } else if (imageFile.size === 0) {
        setFieldFeedback('#proof-image', false, 'Selected file is empty');
        valid = false;
      } else {
        setFieldFeedback('#proof-image', true);
      }
    }

    if (!valid) {
      $btn.prop('disabled', false);
      return;
    }

    const formData = new FormData();
    formData.append('csrf_token', $('#csrf-token').val());
    formData.append('donation_id', $('#donation-id').val());
    formData.append('amount', amount);
    formData.append('image', imageFile);

    $btn.html(
      '<span class="spinner-border spinner-border-sm"></span> Saving...'
    );

    $.ajax({
      url: '../../api/donations/donate.php',
      method: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      dataType: 'json',
      success: (res) => {
        if (res.status === 'success') {
          Swal.fire('', res.message || 'Donation recorded', 'success').then(
            () => location.reload()
          );
        } else {
          Swal.fire('Error', res.message || 'Something went wrong', 'error');
        }
      },
      error: function () {
        Swal.fire('Failed', 'Network error. Please try again.', 'error');
      },
      complete: function () {
        $btn.prop('disabled', false);
      },
    });
  });

  function setFieldFeedback(selector, isValid, message = '') {
    const $field = $(selector);
    const $feedback = $field.next('.invalid-feedback');

    if (isValid) {
      $field.removeClass('is-invalid').addClass('is-valid');
      $feedback.hide();
    } else {
      $field.removeClass('is-valid').addClass('is-invalid');
      if (message) {
        if ($feedback.length) {
          $feedback.text(message).show();
        } else {
          $field.after(`<div class="invalid-feedback">${message}</div>`);
        }
      }
    }
  }

  $('#amount, #proof-image').on('input change', function () {
    $(this).removeClass('is-invalid is-valid');
    $(this).next('.invalid-feedback').hide();
  });
});
