<?php
header('Content-Type: application/json');

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/../../../config/connection.php';

function sendResponse($status, $message) {
  echo json_encode([
    'status' => $status,
    'message' => $message
  ]);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  sendResponse('error', 'Invalid request method');
}

if (!isset($_POST['csrfToken']) || $_POST['csrfToken'] !== $_SESSION['csrfToken']) {
  sendResponse('error', 'Invalid token');
}

if (!isset($_POST['aspirantID'])) {
  sendResponse('error', 'Invalid Aspirant ID');
}

$aspirantID = (int) $_POST['aspirantID'];

try {
  $sql = 'DELETE FROM aspirants WHERE aspirant_id = :aspirant_id';

  $reject = $conn->prepare($sql);
  $reject->execute(['aspirant_id' => $aspirantID]);

  if ($reject->rowCount() === 0) {
    sendResponse('error', 'Aspirant not found');
  }

  sendResponse('success', 'Application rejected');
} catch (Throwable $ex) {
  error_log('Error: ' . $ex->getMessage());
  sendResponse('error', 'Something went wrong. Please try again later');
}
