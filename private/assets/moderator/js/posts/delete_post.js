$(function () {
  $(document).on('click', '.delete-btn', function () {
    const postID = $(this).data('post-id');
    const csrfToken = $(this).data('csrf-token');

    Swal.fire({
      text: 'Are you sure you want to delete this post?',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      icon: 'warning',
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '../actions/posts/delete_post.php',
          method: 'POST',
          dataType: 'json',
          data: {
            postID: postID,
            csrf_token: csrfToken,
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
