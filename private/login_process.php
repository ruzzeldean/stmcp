<?php
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  include '../config/connection.php';

  $query = mysqli_query($conn, "SELECT * FROM admins WHERE username = '$username'");

  if (mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_assoc($query);
    $roleID = $row['role_id'];

    if (password_verify($password, $row['password'])) {
      if (session_status() == PHP_SESSION_NONE) {
        session_start();
      }

      $_SESSION['firstName'] = $row['first_name'];

      if ($roleID === "1") {
        $_SESSION["superGatepass"] = "super";
        echo "super";
      } else if ($roleID === "2") {
        $_SESSION["adminGatepass"] = "admin";
        echo "admin";
      } else if ($roleID === "3") {
        $_SESSION["moderatorGatepass"] = "moderator";
        echo "moderator";
      }

    } else {
      // Incorrect password
      echo "Incorrect username or password.";
    }
  } else {
    // Username not found
    echo "Incorrect username or password.";
  }
} else {
  echo "Intruder!";
}
