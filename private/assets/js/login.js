$("#login-button").click(() => {
  const username = $("#username").val();
  const password = $("#password").val();

  if (username.trim() === "") {
    Swal.fire({
      text: "Username is required.",
      icon: "warning",
    });
  } else if (password.trim() === "") {
    Swal.fire({
      text: "Password is required.",
      icon: "warning",
    });
  } else {
    $.ajax({
      url: "./login_process.php",
      method: "POST",
      data: {
        username: username,
        password: password,
      },
      success: (response) => {
        if (response == "Incorrect username or password.") {
          Swal.fire({
            text: "Incorrect username or password",
            icon: "error",
          });
        } else {
          Swal.fire({
            text: "Access Granted",
            icon: "success",
          }).then(() => {
            if (response == "admin") {
              location.href = "./admin/";
            } else if (response == "moderator") {
              location.href = "./moderator";
            }
          });
        }
      },
    });
  }
});
