$(function () {
  $(document).on('click', '.approve-btn', function () {
    const aspirantID = $(this).data('aspirant-id');
    const csrfToken = $(this).data('csrf-token');
    Swal.fire({
      text: 'Are you sure you want to approve this application?',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      icon: 'question',
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '../actions/aspirants/approve_application.php',
          method: 'POST',
          dataType: 'json',
          data: {
            aspirant_id: aspirantID,
            csrf_token: csrfToken,
          },
          success: (res) => {
            if (res.status === 'success') {
              Swal.fire('Success', res.message, 'success').then(() => {
                location.reload();
              });
            } else {
              Swal.fire('Error', res.message, 'error');
            }
          },
          error: () => {
            Swal.fire('Oops!', 'Something went wrong', 'error');
          },
        });
      }
    });
  });

  $(document).on('click', '.reject-btn', function () {
    const aspirantID = $(this).data('aspirant-id');
    const csrfToken = $(this).data('csrf-token');
    Swal.fire({
      text: 'Are you sure you want to reject this application?',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      icon: 'warning',
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '../actions/aspirants/reject_application.php',
          method: 'POST',
          dataType: 'json',
          data: {
            aspirant_id: aspirantID,
            csrf_token: csrfToken,
          },
          success: (res) => {
            if (res.status === 'success') {
              Swal.fire('Success', res.message, 'success').then(() => {
                location.reload();
              });
            } else {
              Swal.fire('Error', res.message, 'error');
            }
          },
          error: () => {
            Swal.fire('Oops!', 'Something went wrong', 'error');
          },
        });
      }
    });
  });
});
