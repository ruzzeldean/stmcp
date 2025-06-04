// Script for login
$('#login-button').click(() => {
  const username = $('#username').val();
  const password = $('#password').val();

  if (username === "") {
    Swal.fire({
      text: "Username is required.",
      icon: "warning"
    });
  } else if (password === "") {
    Swal.fire({
      text: "Password is required.",
      icon: "warning"
    });
  } else {
    $.ajax({
      url: './login_process.php',
      method: 'POST',
      data: {
        username: username,
        password: password
      },
      success: (response) => {
        Swal.fire({
          title: "Access granted.",
          icon: "success"
        }).then(() => {
          if (response == "super") {
            location.reload();
          } else if (response == "admin") {
            location.href = '../app/views/admin/';
          } else if (response == "moderator") {
            location.href = '../app/views/moderator/';
          }
        });
      }
    });
  }
});