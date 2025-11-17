$(function () {
  $('.reject-btn').prop('disabled', true);
  /* $(document).on('click', '.reject-btn', function () {
    const memberID = $(this).data('member-id');
    const csrfToken = $(this).data('csrf-token');

    Swal.fire({
      text: 'Are you sure you want to delete this application?',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      icon: 'warning',
    }).then((res) => {
      if (res.isConfirmed) {
        $.ajax({
          url: '../actions/official_members/delete_member.php',
          method: 'POST',
          dataType: 'json',
          data: {
            memberID: memberID,
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
  }); */
});
