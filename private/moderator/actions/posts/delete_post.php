<?php
require_once __DIR__ . '/../../../includes/helpers.php';

requireLogin();
requirePost();
requireCsrf();

if (!isset($_POST['postID'])) {
  sendResponse('error', 'Invalid Post ID');
}

$db = new Database();
$postID = (int) $_POST['postID'];

try {
  $sql = 'DELETE FROM posts WHERE post_id = :post_id';
  $db->execute($sql, ['post_id' => $postID]);

  sendResponse('success', 'Post delete successfully');
} catch (Throwable $e) {
  error_log('Failed to delete post: ' . $e->getMessage());
  sendResponse('error', 'An error has occured. Please try again later');
}