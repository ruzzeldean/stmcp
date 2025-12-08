<?php
if (!defined('RENDER_HTML')) {
  header('Content-Type: application/json; charset=utf-8');
}

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/../../config/database.php';

function sendResponse($status, $message, $data = [])
{
  $response = [
    'status' => $status,
    'message' => $message,
    'data' => $data
  ];

  echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  exit;
}

function e($value)
{
  return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function requireLogin()
{
  if (!isset($_SESSION['user_id'])) {
    sendResponse('error', 'You must be logged in');
  }
}

function requireAdmin() {
  if ($_SESSION['role_id'] !== 2) {
    sendResponse('error', 'Unauthorized access');
  }
}

function requirePost()
{
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse('error', 'Invalid request method');
  }
}

function requireCsrf()
{
  if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
    sendResponse('error', 'Invalid CSRF token');
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

