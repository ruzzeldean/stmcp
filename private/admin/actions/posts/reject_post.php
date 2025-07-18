<?php
header('Content-Type: application/json');

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/../../../../config/connection.php';

function sendResponse($status, $message)
{
  echo json_encode(['status' => $status, 'message' => $message]);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  sendResponse('error', 'Invalid request method');
}

if (!isset($_POST['csrfToken']) || $_POST['csrfToken'] !== $_SESSION['csrfToken']) {
  sendResponse('error', 'Invalid token');
}

if (!isset($_POST['postID'])) {
  sendResponse('error', 'Invalid Post ID');
}

if (!isset($_POST['reason'])) {
  sendResponse('error', 'Reason is required');
}

$postID = (int) $_POST['postID'];
$reason = trim($_POST['reason']);

if (empty($reason)) {
  sendResponse('error', 'Reason must not be empty');
}

try {
  $reject = $conn->prepare('UPDATE posts SET reason = :reason, status = "Rejected" WHERE post_id = :post_id');
  $reject->execute(['post_id' => $postID, 'reason' => $reason]);

  if ($reject->rowCount() > 0) {
    sendResponse('success', 'Post successfully rejected');
  } else {
    $check = $conn->prepare('SELECT post_id FROM posts WHERE post_id = :post_id');
    $check->execute(['post_id' => $postID]);

    if ($check->fetch()) {
      sendResponse('success', 'Post is already rejected');
    } else {
      sendResponse('error', 'Post not found');
    }
  }
} catch (Throwable $ex) {
  error_log('Error rejecting post: ' . $ex->getMessage());
  sendResponse('error', 'Something went wrong. Please try again later');
}
