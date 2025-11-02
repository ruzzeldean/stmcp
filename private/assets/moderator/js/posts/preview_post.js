$(function () {
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
});
