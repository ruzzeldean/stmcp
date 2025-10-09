<?php
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// require_once __DIR__ . '/../../config/connection.php';
require_once __DIR__ . '/../classes/database.php';

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

function requireLogin()
{
  if (!isset($_SESSION['userID'])) {
    sendResponse('error', 'You must be logged in');
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
  if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrfToken'] ?? '')) {
    sendResponse('error', 'Invalid CSRF token');
  }
}

function getConversationID($sender, $receiver)
{
  $IDs = [$sender, $receiver];
  sort($IDs);

  return md5(implode('_', $IDs));
}

// $db = new Database();