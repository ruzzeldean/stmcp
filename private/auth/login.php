<?php
session_start();

if (isset($_SESSION['user_id'])) {
  header('Location: ../pages/dashboard.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | STMCP</title>
  <link rel="shortcut icon" href="../assets/images/logo/stmcp_official_back_logo.png" type="image/x-icon">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print" onload="this.media = 'all'" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <link rel="stylesheet" href="../../src/css/adminlte.min.css" />
  <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="login-page bg-body-secondary">
  <div class="login-box">
    <div class="login-logo">
      <img class="w-75" src="../assets/images/logo/one_star_logo.png" alt="">
    </div>
    <div class="card">
      <div class="card-body login-card-body rounded pb-2">
        <h3 class="login-box-msg">Welcome Back!</h3>

        <form id="login-form" novalidate>
          <div class="mb-2">
            <label for="username" class="form-label">Username</label>
            <div class="input-group">
              <div class="input-group-text">
                <i class="fa-solid fa-user"></i>
              </div>
              <input id="username" type="text" class="form-control" placeholder="Enter username" autocomplete="username" required />
            </div>
            <div class="invalid-feedback"></div>
          </div>

          <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
              <div class="input-group-text">
                <i class="fa-solid fa-lock"></i>
              </div>
              <input id="password" type="password" class="form-control" placeholder="Enter password" autocomplete="current-password" required />
            </div>
            <div class="invalid-feedback"></div>
          </div>

          <div class="row mb-3">
            <div>
              <button id="login-btn" type="submit" class="btn btn-primary w-100">Login</button>
            </div>
          </div>
        </form>

        <p class="text-center">
          <a class="text-decoration-none" href="forgot_password.php">Forgot password?</a>
        </p>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script src="../../src/js/adminlte.min.js"></script>
  <script src="../assets/js/shared/sweetalert2.all.min.js"></script>
  <script src="../assets/js/login.js"></script>
</body>

</html>