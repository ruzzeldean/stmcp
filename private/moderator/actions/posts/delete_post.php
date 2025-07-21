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
  $deletePost = $conn->prepare('DELETE FROM posts WHERE post_id = :post_id');
  $deletePost->execute(['post_id' => $postID]);

  sendResponse('success', 'Post delete successfully');
} catch (Throwable $ex) {
  error_log('Failed to delete post: ' . $ex->getMessage());
  sendResponse('error', 'An error has occured. Please try again later');
}