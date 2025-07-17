<?php
header('Content-Type: application/json');

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/../../../config/connection.php';

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
  sendResponse('error', 'Invalid post ID');
}

$postID = $_POST['postID'];

try {
  $stmt = $conn->prepare('SELECT title, category, content, image_path, created_at FROM posts WHERE post_id = :post_id');
  $stmt->execute(['post_id' => $postID]);

  $post = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($post) {
    $post['formattedDate'] = date('F j, Y g:i A', strtotime($post['created_at']));
    sendResponse('success', $post);
  } else {
    sendResponse('error', 'Post not found');
  }
} catch (Throwable $ex) {
  error_log('Error fetching post: ' . $ex->getMessage());
  sendResponse('error', 'Something went wrong. Please try again later');
}
