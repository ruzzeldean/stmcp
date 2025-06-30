<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (
  $_SERVER['REQUEST_METHOD'] === 'POST' &&
  isset($_POST['username']) &&
  isset($_POST['password'])
) {
  $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS));
  $password = $_POST['password'];

  if (empty($username)) {
    echo 'Username is required.';
    exit;
  }

  if (empty($password)) {
    echo 'Password is required.';
    exit;
  }

  require_once '../config/connection.php';

  try {
    $stmt = $conn->prepare('SELECT username, password, first_name, role_id FROM admins WHERE username = :username');
    $stmt->execute(['username' => $username]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($password, $row['password'])) {
      session_regenerate_id(true);

      $_SESSION['firstName'] = $row['first_name'];
      $roleID = $row['role_id'];

      switch ($roleID) {
        case 1:
          $_SESSION['superGatepass'] = 'super';
          echo 'super';
          break;
        case 2:
          $_SESSION['adminGatepass'] = 'admin';
          echo 'admin';
          break;
        case 3:
          $_SESSION['moderatorGatepass'] = 'moderator';
          echo 'moderator';
          break;
        default:
          echo 'Invalid role assigned.';
      }

      exit;
    } else {
      echo 'Incorrect username or password.';
      exit;
    }
  } catch (PDOException $ex) {
    error_log("Login failed: " . $ex->getMessage());
    echo 'An error occured. Please try again later.';
  }
} else {
  echo 'Invalid request.';
  exit;
}
