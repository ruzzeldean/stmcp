<?php
require_once __DIR__ . '/../../../includes/helpers.php';

requireLogin();
requirePost();
requireCsrf();

$db = new Database();

$requiredFields = [
  'user_id',
  'username',
  'role_id',
  'status'
];

$data = [];

foreach ($requiredFields as $field) {
  if (empty($_POST[$field] ?? '')) {
    sendResponse('error', "Missing or empty field: $field");
  }
  $data[$field] = trim($_POST[$field]);
}

$userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);

if (!$userId) {
  sendResponse('error', 'Invalid user id');
}

$updateFields = [
  'username = :username',
  'role_id = :role_id',
  'status = :status'
];

$password = $_POST['password'] ?? '';

if (!empty(trim($password))) {
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  $updateFields[] = 'password = :password';
  $data['password'] = $hashedPassword;
}

$data['user_id'] = $userId;

$sql = 'UPDATE users SET ' . implode(', ', $updateFields) . ' WHERE user_id = :user_id';

try {
  $stmt = $db->execute($sql, $data);

  if ($stmt->rowCount() > 0) {
    sendResponse('success', 'User updated successfully');
  } else {
    sendResponse('success', 'No changes detected');
  }
} catch (Throwable $e) {
  error_log('Error update user: ' . $e);
  sendResponse('error', 'An error occured while updating the member. Please try again later');
}
