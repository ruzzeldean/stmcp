<?php
header('Content-Type: application/json');

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

$db = new Database();