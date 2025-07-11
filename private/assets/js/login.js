$(function () {
  $('#login-btn').click(() => {
    const username = $('#username').val().trim();
    const password = $('#password').val().trim();

    if (username === '') {
      Swal.fire({
        text: 'Username is required.',
        icon: 'warning',
      });
    } else if (password === '') {
      Swal.fire({
        text: 'Password is required.',
        icon: 'warning',
      });
    } else {
      $('#login-btn').prop('disabled', true);

      $.ajax({
        url: './login_process.php',
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
                location.href = './admin/';
              } else if (response.message === '3') {
                location.href = './moderator/';
              } else if (response.message === '4') {
                location.href = './member/';
              }
            });
          } else {
            Swal.fire('Error', response.message, 'error');
            $('#login-btn').prop('disabled', false);
          }
        },
        error: () => {
          Swal.fire('Oops!', 'Something went wrong. Please try again later', 'error');
          
          $('#login-btn').prop('disabled', false);
        },
      });
    }
  });
});
