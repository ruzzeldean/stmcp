<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Star Touring Motorcycle Club Philippines</title>
  <link rel="shortcut icon" href="./assets/shared/images/logo/logo.png" type="image/x-icon">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- AdminLTE 4 -->
  <link rel="stylesheet" href="./assets/shared/css/adminlte.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="./assets/shared/css/style.css">
</head>

<body>
  <div id="wrapper" class="bg-light p-3">
    <div id="login-form" class="bg-white rounded shadow p-4">
      <div class="text-center">
        <img id="login-logo" class="img-fluid mb-4" src="./assets/shared/images/logo/one-star-logo.png" alt="One star logo">
      </div>

      <h2 class="mb-3 text-center">Welcome Back</h2>

      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fa-solid fa-user"></i>
          </span>
        </div>
        <input type="text" id="username" class="form-control" placeholder="Username" autocomplete="on">
      </div>

      <div class="input-group mb-4">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fa-solid fa-lock"></i>
          </span>
        </div>
        <input type="password" id="password" class="form-control" placeholder="Password">
      </div>

      <div class="form-group">
        <button id="login-btn" class="btn btn-primary w-100">Login</button>
      </div>

      <p class="mb-0 text-center"><a href="">Forgot password?</a></p>
    </div> <!-- End of #login-form -->
  </div> <!-- End of #wrapper -->

  <!-- jQuery min 3.7.1 -->
  <script src="./assets/shared/js/jquery.min.js"></script>
  <!-- AdminLTE 4 -->
  <script src="./assets/shared/js/adminlte.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.0/dist/sweetalert2.all.min.js"></script>
  <!-- Custom script -->
  <script src="./assets/shared/js/login.js"></script>
</body>

</html>