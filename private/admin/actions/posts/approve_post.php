<?php
require_once __DIR__ . '/../../../includes/helpers.php';

requireLogin();
requirePost();
requireCsrf();

if (!isset($_POST['postID'])) {
  sendResponse('error', 'Invalid Post ID');
}

$postID = (int) $_POST['postID'];

$db = new Database();

try {
  $sql = 
    'UPDATE posts
    SET status = "Published", reason = NULL
    WHERE post_id = :post_id';
  $stmt = $db->execute($sql, ['post_id' => $postID]);

  if ($stmt->rowCount() > 0) {
    sendResponse('success', 'Post is now published');
  } else {
    $sql = 'SELECT post_id
            FROM posts
            WHERE post_id = :post_id';
    $check = $db->fetchOne($sql, ['post_id' => $postID]);

    if ($check) {
      sendResponse('success', 'Post is already published');
    } else {
      sendResponse('error', 'Post not found');
    }
  }
} catch (Throwable $e) {
  error_log('Error approving post: ' . $e->getMessage());
  sendResponse('error', 'Something went wrong. Please try again later');
}
