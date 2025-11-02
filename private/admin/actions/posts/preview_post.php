<?php
require_once __DIR__ . '/../../../includes/helpers.php';

requireLogin();
requirePost();
requireCsrf();

if (!isset($_POST['postID'])) {
  sendResponse('error', 'Invalid post ID');
}

$postID = $_POST['postID'];

$db = new Database();

try {
  $sql = 
    'SELECT title, category, content, image_path, created_at
    FROM posts WHERE post_id = :post_id';

  $post = $db->fetchOne($sql, ['post_id' => $postID]);

  if ($post) {
    $post['formattedDate'] = date('F j, Y g:i A', strtotime($post['created_at']));
    sendResponse('success', $post);
  } else {
    sendResponse('error', 'Post not found');
  }
} catch (Throwable $e) {
  error_log('Error fetching post: ' . $e->getMessage());
  sendResponse('error', 'Something went wrong. Please try again later');
}
