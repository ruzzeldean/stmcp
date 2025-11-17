$(function () {
  $('#login-btn').click(() => {
    const username = $('#username').val().trim();
    const password = $('#password').val().trim();

    if (!username) {
      Swal.fire('', 'Username is required', 'warning');
    } else if (!password) {
      Swal.fire('', 'Password is required', 'warning');
    } else {
      $('#login-btn').prop('disabled', true);

      $.ajax({
        url: './login_handler.php',
        method: 'POST',
        dataType: 'json',
        data: {
          username: username,
          password: password,
        },
        success: (response) => {
          if (response.status === 'success') {
            Swal.fire('Success', 'Access Granted', 'success').then(() => {
              if (response.message === '1') {
                location.href = './super/';
              } else if (response.message === '2') {
                location.href = './admin/pages/';
              } else if (response.message === '3') {
                location.href = './moderator/pages/';
              } else if (response.message === '4' || response.message === '5') {
                location.href = './member/pages/';
              }
            });
          } else {
            Swal.fire('Error', response.message, 'error');
            $('#login-btn').prop('disabled', false);
          }
        },
        error: () => {
          Swal.fire(
            'Oops!',
            'Something went wrong. Please try again later',
            'error'
          );

          $('#login-btn').prop('disabled', false);
        },
      });
    }
  });
});
