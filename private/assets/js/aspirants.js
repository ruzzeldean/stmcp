$(function () {
  $(document).on('click', '.approve-btn', function () {
    const aspirantID = $(this).data('aspirant-id');
    Swal.fire({
      text: 'Are you sure you want to approve this application?',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      icon: 'question',
    }).then(result => {
      if (result.isConfirmed) {
        $.ajax({
          url: './actions/approve_application.php',
          method: 'POST',
          dataType: 'json',
          data: {
            aspirantID: aspirantID,
          },
          success: response => {
            if (response.status == 'success') {
              Swal.fire('Success', response.message, 'success').then(() => {
                location.reload();
              });
            } else {
              Swal.fire('Error', response.message, 'error');
            }
          },
          error: () => {
            Swal.fire('Oops!', 'Something went wrong', 'error');
          }
        });
      }
    });
  });

  $(document).on('click', '.reject-btn', function () {
    const aspirantID = $(this).data('aspirant-id');
    Swal.fire({
      text: 'Are you sure you want to reject this application?',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      icon: 'warning',
    }).then(result => {
      if (result.isConfirmed) {
        $.ajax({
          url: './actions/reject_application.php',
          method: 'POST',
          dataType: 'json',
          data: {
            aspirantID: aspirantID,
          },
          success: response => {
            if (response.status == 'success') {
              Swal.fire('Success', response.message, 'success').then(() => {
                location.reload();
              });
            } else {
              Swal.fire('Error', response.message, 'error');
            }
          },
          error: (error) => {
            Swal.fire('Oops!', 'Something went wrong', 'error');
          }
        });
      }
    });
  });
});
