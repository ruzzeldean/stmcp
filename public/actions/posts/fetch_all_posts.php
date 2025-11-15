<?php
require_once __DIR__ . '/../../includes/helpers.php';

if (
  $_SERVER['REQUEST_METHOD'] !== 'GET' ||
  empty($_SERVER['HTTP_X_REQUESTED_WITH']) ||
  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'
) {
  http_response_code(403);
  sendResponse('error', 'Direct access is not allowed.');
}

try {
  $db = new Database();
  $conn = $db->getConnection();

  $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
  $limit = 6;
  $offset = ($page - 1) * $limit;

  $countSql = 'SELECT COUNT(*) AS total FROM posts WHERE status = :status';
  $count = $db->fetchOne($countSql, [':status' => 'Published']);
  $totalPosts = $count ? (int) $count['total'] : 0;
  $totalPages = $totalPosts > 0 ? ceil($totalPosts / $limit) : 1;

  $sql = 'SELECT post_id, title, category, image_path, created_at
          FROM posts
          WHERE status = :status
          ORDER BY created_at DESC
          LIMIT :limit OFFSET :offset';

  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':status', 'Published', PDO::PARAM_STR);
  $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
  $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
  $stmt->execute();
  $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

  sendResponse('success', 'Posts fetched successfully', [
    'posts' => $posts,
    'pagination' => [
      'currentPage' => $page,
      'totalPages' => $totalPages,
      'totalPosts' => $totalPosts
    ]
  ]);
} catch (Throwable $e) {
  error_log('Error fetching public posts: ' . $e);
  http_response_code(500);
  sendResponse('error', 'Error fetching posts');
}
