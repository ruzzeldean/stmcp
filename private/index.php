<?php
session_start();

if (isset($_SESSION['user_id'])) {
  header('Location: pages/dashboard.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>STMCP</title>
  <link rel="shortcut icon" href="../assets/images/logo/stmcp_official_back_logo.png" type="image/x-icon">
</head>
<body>
  <script>window.location.href = 'auth/login.php'</script>
</body>
</html>