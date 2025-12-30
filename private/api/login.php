<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['message' => 'Method not allowed']);
  exit;
}

// CSRF validation

$input = json_decode(file_get_contents('php://input'), true);

$username = trim($input['username'] ?? '');
$password = $input['password'] ?? '';

if (!$username) {
  http_response_code(422);
  echo json_encode(['message' => 'Username is required']);
  exit;
}

if (!$password) {
  http_response_code(422);
  echo json_encode(['message' => 'Password is required']);
  exit;
}

try {
  $db = new Database();

  $sql = 'SELECT
            u.user_id,
            u.username,
            u.password,
            u.status,
            p.first_name,
            p.last_name,
            CONCAT(p.first_name, " ", p.last_name) AS full_name,
            r.role_id,
            r.role
          FROM users AS u
          LEFT JOIN people AS p
            ON u.person_id = p.person_id
          LEFT JOIN roles AS r
            ON u.role_id = r.role_id
          WHERE username = :username
          LIMIT 1';
  $user = $db->fetchOne($sql, [':username' => $username]);

  if (!$user || !password_verify($password, $user['password'])) {
    http_response_code(401);
    echo json_encode(['message' => 'Incorrect username or password']);
    exit;
  }

  if ($user['status'] === 'Disabled') {
    http_response_code(403);
    echo json_encode(['message' => 'Account is disabled']);
    exit;
  }

  session_regenerate_id(true);

  $_SESSION['user_id'] = $user['user_id'];
  $_SESSION['first_name'] = $user['first_name'];
  $_SESSION['last_name'] = $user['last_name'];
  $_SESSION['full_name'] = $user['full_name'];
  $_SESSION['role_id'] = $user['role_id'];
  $_SESSION['role'] = $user['role'];
  $_SESSION['last_activity'] = time();

  http_response_code(200);
  echo json_encode([
    'message' => 'Login successful',
    'redirect' => '../pages/dashboard.php'
  ]);
} catch (PDOException $e) {
  error_log("Login error: $e");
  http_response_code(500);
  echo json_encode(['message' => 'Server error']);
} catch (Throwable $e) {
  error_log("Login error: $e");
  http_response_code(500);
  echo json_encode(['message' => 'Server error']);
}
