<?php
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/../../config/database.php';

function sendResponse($message, $data = [], $status = 'success')
{
  $response = [
    'message' => $message,
    'data' => $data,
    'status' => $status
  ];

  echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  exit;
}

function requireLogin()
{
  if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    sendResponse('You must be logged in');
  }
}

function requirePost()
{
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    sendResponse('Invalid request method');
  }
}

/* function requireCsrf()
{
  $sessionToken = $_SESSION['csrf_token'] ?? null;
  $requestToken = $_POST['csrf_token'] ?? null;

  if (
    !$requestToken ||
    !$sessionToken ||
    !hash_equals($sessionToken, $requestToken)
  ) {
    http_response_code(403);
    sendResponse('Invalid CSRF token');
  }
} */

function requireCsrf(): void
{
  $sessionToken = $_SESSION['csrf_token'] ?? null;
  $requestToken = null;

  // JSON request
  if (
    isset($_SERVER['CONTENT_TYPE']) &&
    str_contains($_SERVER['CONTENT_TYPE'], 'application/json')
  ) {
    $payload = json_decode(file_get_contents('php://input'), true);
    $requestToken = $payload['csrf_token'] ?? null;
  }

  // FormData / normal POST
  if ($requestToken === null) {
    $requestToken = $_POST['csrf_token'] ?? null;
  }

  if (
    !$sessionToken ||
    !$requestToken ||
    !hash_equals($sessionToken, $requestToken)
  ) {
    http_response_code(403);
    sendResponse('Invalid CSRF token', [], 'error');
    exit;
  }
}



function requireAdmin()
{
  if ($_SESSION['role_id'] !== 1 && $_SESSION['role_id'] !== 2) {
    http_response_code(403);
    sendResponse('Unauthorized access'); // Unauthorized access
  }
}

function requireModerator()
{
  if ($_SESSION['role_id'] !== 1 && $_SESSION['role_id'] !== 3) {
    http_response_code(403);
    sendResponse('Unauthorized access'); // Unauthorized access
  }
}

function requireOfficialMember()
{
  if (
    $_SESSION['role_id'] !== 1 &&
    $_SESSION['role_id'] !== 2 &&
    $_SESSION['role_id'] !== 3 &&
    $_SESSION['role_id'] !== 4 ||
    $_SESSION['role_id'] === 5
  ) {
    http_response_code(403);
    sendResponse('Unauthorized access'); // Unauthorized access
  }
}

function requireAdminModerator()
{
  if (
    $_SESSION['role_id'] !== 1 &&
    $_SESSION['role_id'] !== 2 &&
    $_SESSION['role_id'] !== 3 ||
    $_SESSION['role_id'] === 4 ||
    $_SESSION['role_id'] === 5
  ) {
    http_response_code(403);
    sendResponse('Unauthorized access'); // Unauthorized access
  }
}

function getConversationID($sender, $receiver)
{
  $IDs = [$sender, $receiver];
  sort($IDs);

  return md5(implode('_', $IDs));
}

function validateDate($date)
{
  $d = DateTime::createFromFormat('Y-m-d', $date);
  return $d && $d->format('Y-m-d') === $date;
}

function e($value)
{
  return htmlspecialchars(
    $value ?? '',
    ENT_QUOTES | ENT_SUBSTITUTE,
    'UTF-8'
  );
}
