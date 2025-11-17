<?php
require_once __DIR__ . '/includes/helpers.php';

requirePost();

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($username)) {
  sendResponse('error', 'Username is required');
}

if (empty($password)) {
  sendResponse('error', 'Password is required');
}

try {
  $db = new Database();

  $sql = 'SELECT
            u.user_id,
            u.password,
            u.status,
            p.first_name,
            p.last_name,
            r.role_id
          FROM
              users AS u
          LEFT JOIN
              people AS p ON u.person_id = p.person_id
          LEFT JOIN
              roles AS r ON u.role_id = r.role_id
          WHERE
              u.username = :username
          LIMIT 1';

  $user = $db->fetchOne($sql, ['username' => $username]);

  if (!$user || !password_verify($password, $user['password'])) {
    sendResponse('error', 'Incorrect username or password');
  }

  session_regenerate_id(true);

  $_SESSION['user_id'] = $user['user_id'];
  $_SESSION['first_name'] = $user['first_name'];
  $_SESSION['last_name'] = $user['last_name'];
  $_SESSION['role_id'] = $user['role_id'];

  switch ($_SESSION['role_id']) {
    case 1:
      $_SESSION['gate_pass'] = 'super';
      sendResponse('success', '1');
      break;

    case 2:
      $_SESSION['gate_pass'] = 'admin';
      sendResponse('success', '2');
      break;

    case 3:
      $_SESSION['gate_pass'] = 'moderator';
      sendResponse('success', '3');
      break;

    case 4:
      $_SESSION['gate_pass'] = 'member';
      sendResponse('success', '4');
      break;

    case 5:
      $_SESSION['gate_pass'] = 'aspirant';
      sendResponse('success', '5');
      break;

    default:
      sendResponse('error', 'Invalid role assigned');
  }
} catch (Throwable $e) {
  error_log('Login failed: ' . $e);
  sendResponse('error', 'An error occurred. Please try again later');
}
