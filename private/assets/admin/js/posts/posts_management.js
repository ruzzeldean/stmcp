$(function () {
  // preview button
  $(document).on('click', '.preview-btn', function () {
    const postID = $(this).data('post-id');
    const csrfToken = $(this).data('csrf-token');

    $.ajax({
      url: '../actions/posts/preview_post.php',
      method: 'POST',
      dataType: 'json',
      data: {
        postID: postID,
        csrf_token: csrfToken,
      },
      success: (data) => {
        if (data.status === 'success') {
          const imagePath = data.message.image_path
            ? '/stmcp/uploads/posts/' + data.message.image_path
            : '/stmcp/uploads/posts/placeholder.png';

          $('#post-title').text(data.message.title);
          $('#post-date').text(data.message.formattedDate);
          $('#post-category').text(data.message.category);
          $('#post-image').attr('src', imagePath);
          $('#post-content').text(data.message.content);

          $('.approve-btn, .reject-btn').data('post-id', postID);
          $('.approve-btn, .reject-btn').data('csrf-token', csrfToken);

          $('#preview-modal').modal('show');
        } else {
          Swal.fire('Error', data.message, 'error');
        }
      },
    });
  });

  // approve button
  $(document).on('click', '.approve-btn', function () {
    const postID = $(this).data('post-id');
    const csrfToken = $(this).data('csrf-token');

    $('#preview-modal').modal('hide');

    Swal.fire({
      text: 'Are you sure you want to approve this post?',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      icon: 'question',
      allowOutsideClick: false,
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '../actions/posts/approve_post.php',
          method: 'POST',
          dataType: 'json',
          data: {
            postID: postID,
            csrf_token: csrfToken,
          },
          success: (res) => {
            if (res.status === 'success') {
              Swal.fire('Success', res.message, 'success').then(() => {
                location.reload();
              });
            } else {
              Swal.fire('Error', res.message, 'error').then(() => {
                $('#preview-modal').modal('show');
              });
            }
          },
          error: () => {
            Swal.fire('Oops!', 'Something went wrong', 'error').then(() => {
              $('#preview-modal').modal('show');
            });
          },
        });
      } else {
        $('#preview-modal').modal('show');
      }
    });
  });

  // reject button
  $(document).on('click', '.reject-btn', function () {
    const postID = $(this).data('post-id');
    const csrfToken = $(this).data('csrf-token');

    $('#preview-modal').modal('hide');

    Swal.fire({
      text: 'Are you sure you want to reject this post?',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      icon: 'warning',
      allowOutsideClick: false,
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          input: 'text',
          inputLabel: 'Reason for rejection',
          inputPlaceholder: 'Enter reason here...',
          inputValidator: (value) => {
            if (!value) {
              return 'This field cannot be blank';
            }
          },
          showCancelButton: true,
          confirmButtonText: 'Continue',
          allowOutsideClick: false,
        }).then((result) => {
          const reason = result.value;

          if (result.isConfirmed) {
            $.ajax({
              url: '../actions/posts/reject_post.php',
              method: 'POST',
              dataType: 'json',
              data: {
                postID: postID,
                reason: reason,
                csrf_token: csrfToken,
              },
              success: (res) => {
                if (res.status === 'success') {
                  Swal.fire('Success', res.message, 'success').then(() => {
                    location.reload();
                  });
                } else {
                  Swal.fire('Error', res.message, 'error').then(() => {
                    $('#preview-modal').modal('show');
                  });
                }
              },
              error: () => {
                Swal.fire('Oops!', 'Something went wrong', 'error').then(() => {
                  $('#preview-modal').modal('show');
                });
              },
            });
          } else {
            $('#preview-modal').modal('show');
          }
        });
      } else {
        $('#preview-modal').modal('show');
      }
    });
  });
});
