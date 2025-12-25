<?php
include __DIR__ . '/../../includes/helpers.php';

requireLogin();
requireAdmin();

$db = new Database();
$method = $_SERVER['REQUEST_METHOD'];
$action = 'fetchUserAccounts';
$input = [];

if ($method === 'POST') {
  $input = json_decode(file_get_contents('php://input'), true) ?? [];
  $action = $input['action'] ?? $action;
}

switch ($action) {
  case 'fetchUserAccounts':
    fetchUserAccounts($db);
    break;

  case 'updateUserAccount':
    updateUserAccount($db);
    break;

  case 'disable':
    disableUserAccount($db);
    break;
}

function fetchUserAccounts(Database $db)
{
  $limit = 10;
  $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
  $offset = ($currentPage - 1) * $limit;

  try {
    $totalUsers = $db->fetchOne('SELECT COUNT(*) as total FROM users');
    $totalRows = $totalUsers['total'] ?? 0;
    $totalPages = ceil($totalRows / $limit);

    $sql = "SELECT
              p.first_name,
              p.last_name,
              u.username,
              u.user_id,
              u.role_id,
              u.status,
              u.updated_at
            FROM people AS p
            LEFT JOIN users AS u
              ON p.person_id = u.person_id
            WHERE u.role_id != 1
            LIMIT $limit OFFSET $offset";
    $userAccounts = $db->fetchAll($sql);

    $roleMap = [
      1 => 'Super Admin',
      2 => 'Admin',
      3 => 'Moderator',
      4 => 'Member',
      5 => 'Aspirant'
    ];

    $userAccounts = array_map(function ($user) use ($roleMap) {
      $user['role_name'] = $roleMap[$user['role_id']] ?? 'Unknown';
      unset($user['role_id']);
      return $user;
    }, $userAccounts);

    sendResponse('Records successfully fetched', [
      'user_accounts' => $userAccounts,
      'total_pages' => (int)$totalPages,
      'current_page' => (int)$currentPage
    ]);
  } catch (PDOException $e) {
    error_log("Server error: $e");
    http_response_code(500);
    sendResponse('Server error');
  } catch (Throwable $e) {
    error_log("Error fetching user accounts error: $e");
    http_response_code(500);
    sendResponse('Error fetching user accounts');
  }
}

function updateUserAccount(Database $db)
{
  try {
    $input = json_decode(file_get_contents('php://input'), true) ?? [];

    $requiredFields = ['user_id', 'username', 'role_id', 'status'];
    foreach ($requiredFields as $field) {
      if (empty($input[$field]) ?? null) {
        http_response_code(422);
        sendResponse('Missing or empty required fields', [], 'warning');
      }
    }

    $userId = filter_var($input['user_id'], FILTER_VALIDATE_INT);
    if (!$userId || $userId <= 0) {
      http_response_code(422);
      sendResponse('Invalid user ID', [], 'error');
    }

    $username = trim($input['username']);
    $roleId = (int) $input['role_id'];
    $status = trim($input['status']);

    $newPassword = $input['new_password'] ?? '';
    $hashedPassword = null;

    if (!empty(trim($newPassword))) {
      if (strlen($newPassword) < 8) {
        sendResponse('Password must be at least 8 characters long', [], 'warning');
      }
      $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    }

    $updateFields = [
      'username = :username',
      'role_id = :role_id',
      'status = :status'
    ];

    $params = [
      ':username' => $username,
      ':role_id' => $roleId,
      ':status' => $status,
      ':user_id' => $userId
    ];

    if ($hashedPassword !== null) {
      $updateFields[] = 'password = :password';
      $params[':password'] = $hashedPassword;
    }

    $sql = 'UPDATE users
            SET ' . implode(', ', $updateFields) . '
            WHERE user_id = :user_id';

    $stmt = $db->execute($sql, $params);

    if ($stmt->rowCount() > 0) {
      sendResponse('User updated successfully');
    } else {
      $check = $db->fetchOne(
        'SELECT user_id FROM users WHERE user_id = ?',
        [$userId]
      );
      if (!$check) {
        sendResponse('User not found', [], 'warning');
      }
      sendResponse('No changes made', [], 'info');
    }
  } catch (PDOException $e) {
    error_log("Server error: $e");
    http_response_code(500);
    sendResponse('Server error');
  } catch (Throwable $e) {
    error_log("Error updating user account: $e");
    http_response_code(500);
    sendResponse('Error updating user account');
  }
}

function disableUserAccount(Database $db)
{
  $input = json_decode(file_get_contents('php://input'), true) ?? [];
  $userId = $input['user_id'] ?? null;
  if (empty($userId)) {
    http_response_code(422);
    sendResponse('Missing or empty required fields', [], 'warning');
  }

  try {
    $sql = 'UPDATE users
            SET status = "Disabled"
            WHERE user_id = ?';
    if (!$db->execute($sql, [$userId])) {
      sendResponse('Error disabling user account', [], 'error');
    }
    sendResponse('User account disabled');
  } catch (Throwable $e) {
    error_log("Error disabling user account: $e");
  }
}
