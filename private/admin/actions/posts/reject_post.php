<?php
require_once __DIR__ . '/../../../includes/helpers.php';

requireLogin();
requirePost();
requireCsrf();

if (!isset($_POST['postID'])) {
  sendResponse('error', 'Invalid Post ID');
}

if (!isset($_POST['reason'])) {
  sendResponse('error', 'Reason is required');
}

if (empty($_POST['reason'])) {
  sendResponse('error', 'Reason must not be empty');
}

$postID = (int) $_POST['postID'];
$reason = trim($_POST['reason']);
$db = new Database();

try {
  $sql =
    'UPDATE posts
    SET reason = :reason, status = "Rejected"
    WHERE post_id = :post_id';
  $data = ['post_id' => $postID, 'reason' => $reason];
  $stmt = $db->execute($sql, $data);

  if ($stmt->rowCount() > 0) {
    sendResponse('success', 'Post successfully rejected');
  } else {
    $sql =
      'SELECT post_id FROM posts 
      WHERE post_id = :post_id';
    $stmt = $db->fetchOne($sql, ['post_id' => $postID]);

    if ($stmt) {
      sendResponse('success', 'Post is already rejected');
    } else {
      sendResponse('error', 'Post not found');
    }
  }
} catch (Throwable $e) {
  error_log('Error rejecting post: ' . $e->getMessage());
  sendResponse('error', 'Something went wrong. Please try again later');
}
