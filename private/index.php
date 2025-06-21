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
  <title>Login | STMCP</title>
  <!-- AdminLTE 4 -->
  <link rel="stylesheet" href="./assets/css/adminlte.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
  <div id="wrapper" class="bg-light p-3">
    <div id="login-form" class="bg-white rounded shadow p-4">
      <div class="text-center">
        <img id="login-logo" class="img-fluid mb-4" src="./assets/img/logo/one-star-logo.png" alt="One star logo">
      </div>

      <h2 class="mb-3 text-center">Welcome Back</h2>

      <div class="form-group">
        <label for="username">Username</label>
        <input class="form-control" type="text" id="username" placeholder="Enter username">
      </div>

      <div class="form-group mb-4">
        <label for="password">Password</label>
        <input class="form-control" type="password" id="password" placeholder="Enter password">
      </div>

      <div class="form-group">
        <button id="login-button" class="btn btn-primary w-100">Login</button>
      </div>

      <p class="mb-0 text-center"><a href="">Forgot password?</a></p>
    </div> <!-- End of #login-form -->
  </div> <!-- End of #wrapper -->

  <!-- jQuery min 3.7.1 -->
  <script src="./assets/js/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="./assets/js/adminlte.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.0/dist/sweetalert2.all.min.js"></script>
  <!-- Custom script -->
  <script src="./assets/js/script.js"></script>
</body>

</html>