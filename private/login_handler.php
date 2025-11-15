<?php
require_once __DIR__ . '/includes/helpers.php';

requirePost();

if (!isset($_POST['username']) || !isset($_POST['password'])) {
  sendResponse('error', 'Invalid username or password');
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($username)) {
  sendResponse('error', 'Username is required');
}

if (empty($password)) {
  sendResponse('error', 'Password is required');
}

$db = new Database();

try {
  $sql = 'SELECT
            u.user_id,
            u.username,
            u.password,
            u.role_id,
            u.member_id,
            u.member_type
          FROM users AS u
          WHERE u.username = :username
          LIMIT 1';

  $user = $db->fetchOne($sql, ['username' => $username]);

  if (!$user || !password_verify($password, $user['password'])) {
    sendResponse('error', 'Incorrect username or password');
  }

  $memberTable = $user['member_type'] === 'official' ? 'official_members' : 'aspirants';
  $memberIdCol = $user['member_type'] === 'official' ? 'member_id' : 'aspirant_id';

  $sql = "SELECT first_name, last_name
          FROM {$memberTable}
          WHERE {$memberIdCol} = :member_id
          LIMIT 1";
  $member = $db->fetchOne($sql, ['member_id' => $user['member_id']]);

  if (!$member) {
    sendResponse('error', 'Member record not found');
  }

  session_regenerate_id(true);

  $_SESSION['user_id'] = $user['user_id'];
  $_SESSION['first_name'] = $member['first_name'];
  $_SESSION['last_name'] = $member['last_name'];
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
  error_log('Login failed: ' . $e->getMessage());
  sendResponse('error', 'An error occurred. Please try again later');
}
