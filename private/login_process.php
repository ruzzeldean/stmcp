<?php
header('Content-Type: application/json');

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/../config/connection.php';

function sendResponse($status, $message)
{
  echo json_encode(['status' => $status, 'message' => $message]);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  sendResponse('error', 'Invalid request method');
}

if (!isset($_POST['username']) || !isset($_POST['password'])) {
  sendResponse('error', 'Invalid username or password');
}

$username = trim($_POST['username']);
$password = trim($_POST['password']);

if (empty($username)) {
  sendResponse('error', 'Username is required');
}

if (empty($password)) {
  sendResponse('error', 'Password is required');
}

try {
  $login = $conn->prepare('SELECT admin_id, username, password, first_name, role_id FROM admins WHERE username = :username');
  $login->execute(['username' => $username]);

  $row = $login->fetch();

  if ($row && password_verify($password, $row['password'])) {
    session_regenerate_id(true);

    $_SESSION['adminID'] = $row['admin_id'];
    $_SESSION['firstName'] = $row['first_name'];
    $_SESSION['roleID'] = $row['role_id'];
    $roleID = $_SESSION['roleID'];

    switch ($roleID) {
      case 1:
        $_SESSION['superGatepass'] = 'super';
        sendResponse('success', '1');
        break;

      case 2:
        $_SESSION['adminGatepass'] = 'admin';
        sendResponse('success', '2');
        break;

      case 3:
        $_SESSION['moderatorGatepass'] = 'moderator';
        sendResponse('success', '3');
        break;

      case 4:
        $_SESSION['memberGatepass'] = 'member';
        sendResponse('success', '4');
        break;

      default:
        sendResponse('error', 'Invalid role assigned');
    }
  } else {
    sendResponse('error', 'Incorrect username or password');
  }
} catch (Throwable $ex) {
  error_log('Login failed: ' . $ex->getMessage());
  sendResponse('error', 'An error occured. Please try again later');
}
