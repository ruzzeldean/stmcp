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

$postID = (int) $_POST['postID'];

try {
  $approve = $conn->prepare('UPDATE posts SET status = "Published"  WHERE post_id = :post_id');
  $approve->execute(['post_id' => $postID]);

  if ($approve->rowCount() > 0) {
    sendResponse('success', 'Post is now published');
  } else {
    $check = $conn->prepare('SELECT post_id FROM posts WHERE post_id = :post_id');
    $check->execute(['post_id' => $postID]);

    if ($check->fetch()) {
      sendResponse('success', ' Post is already published');
    } else {
      sendResponse('error', 'Post not found');
    }
  }
} catch (Throwable $ex) {
  error_log('Error approving post: ' . $ex->getMessage());
  sendResponse('error', 'Something went wrong. Please try again later');
}
