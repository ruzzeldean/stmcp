$(function () {
  $(document).on('click', '.reject-btn', function () {
    const memberID = $(this).data('member-id');
    const csrfToken = $(this).data('csrf-token');
    Swal.fire({
      text: 'Are you sure you want to delete this application?',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      icon: 'warning',
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '../actions/official_members/delete_member.php',
          method: 'POST',
          dataType: 'json',
          data: {
            memberID: memberID,
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
});
