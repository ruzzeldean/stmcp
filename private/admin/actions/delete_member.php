<?php
header('Content-Type: application/json');

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

if (!isset($_POST['memberID'])) {
  sendResponse('error', 'Invalid Member ID');
}

$memberID = (int)$_POST['memberID'];

try {
  $sql = 'DELETE FROM official_members WHERE member_id = :member_id';
  $delete = $conn->prepare($sql);
  $delete->execute(['member_id' => $memberID]);
  sendResponse('success', 'Member deleted successfully');
} catch (Throwable $ex) {
  error_log("Error: " . $ex->getMessage());
  sendResponse('error', 'An error has occured. Please try again later');
}
