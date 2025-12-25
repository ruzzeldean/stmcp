<?php
include __DIR__ . '/../../includes/helpers.php';

requireLogin();
/* requireCsrf(); */
requireAdminModerator();

$userId = $_SESSION['user_id'];

$method = $_SERVER['REQUEST_METHOD'];
$action = 'fetchPosts';
$input = [];

if ($method === 'POST') {
  $input = json_decode(file_get_contents('php://input'), true) ?? [];
  $action = $input['action'] ?? $action;
}

switch ($action) {
  case 'fetchPosts':
    fetchPosts();
    break;

  case 'approvePost':
    requireAdmin();
    approvePost();
    break;

  case 'rejectPost':
    requireAdmin();
    rejectPost();
    break;

  default:
    http_response_code(400);
    sendResponse('Invalid action');
}

function fetchPosts($db = new Database)
{
  $limit = 10;
  $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
  $offset = ($currentPage - 1) * $limit;

  try {
    $totalPosts = $db->fetchOne('SELECT COUNT(*) as total FROM posts');
    $totalRows = $totalPosts['total'] ?? 0;
    $totalPages = ceil($totalRows / $limit);

    $sql =  "SELECT
              posts.post_id,
              posts.title,
              posts.category,
              posts.content,
              posts.image_path,
              posts.status,
              posts.created_at,
              CONCAT(p.first_name, ' ', p.last_name) AS created_by
            FROM posts
            INNER JOIN users AS u
              ON posts.created_by = u.user_id
            INNER JOIN people AS p
              ON u.person_id = p.person_id
            ORDER BY posts.created_at DESC
            LIMIT $limit OFFSET $offset";
    $posts = $db->fetchAll($sql);

    sendResponse('Posts successfully fetched', [
      'posts' => $posts,
      'total_pages' => (int)$totalPages,
      'current_page' => (int)$currentPage
    ]);
  } catch (PDOException $e) {
    http_response_code(500);
    error_log("Server error: $e");
    sendResponse('Server error');
  } catch (Throwable $e) {
    http_response_code(500);
    error_log("Error fetching posts: $e");
    sendResponse('Error fetching posts');
  }
}

function approvePost($db = new Database)
{
  $input = json_decode(file_get_contents('php://input'), true) ?? [];
  $postId = $input['post_id'] ?? null;

  if (!$postId) {
    http_response_code(422);
    sendResponse('Missing post ID', [], 'error');
  }

  try {
    $sql = 'UPDATE posts
            SET status = "Published", reason = NULL
            WHERE post_id = :post_id';
    $stmt = $db->execute($sql, ['post_id' => $postId]);

    if ($stmt->rowCount() > 0) {
      sendResponse('Post is now published');
    } else {
      $sql = 'SELECT post_id
            FROM posts
            WHERE post_id = :post_id';
      $check = $db->fetchOne($sql, ['post_id' => $postId]);

      if ($check) {
        sendResponse('Post is already published', [], 'info');
      } else {
        http_response_code(422);
        sendResponse('Post not found', [], 'error');
      }
    }
  } catch (PDOException $e) {
    http_response_code(500);
    error_log('Error approving post: ' . $e->getMessage());
    sendResponse('Something went wrong. Please try again later', [], 'error');
  }
}

function rejectPost($db = new Database)
{
  $input = json_decode(file_get_contents('php://input'), true) ?? [];
  $postId = $input['post_id'] ?? null;

  if (!$postId) {
    http_response_code(422);
    sendResponse('Missing post ID', [], 'error');
  }

  try {
    $sql = 'UPDATE posts
            SET status = "Rejected"
            WHERE post_id = ?';
    $stmt = $db->execute($sql, [$postId]);

    if ($stmt->rowCount() > 0) {
      sendResponse('Post rejected successfully');
    } else {
      $sql = 'SELECT post_id
            FROM posts
            WHERE post_id = ?';
      $check = $db->fetchOne($sql, [$postId]);

      if ($check) {
        sendResponse('Post is already rejected', [], 'info');
      } else {
        http_response_code(422);
        sendResponse('Post not found', [], 'error');
      }
    }
  } catch (Throwable $e) {
    error_log('Error rejecting post: ' . $e->getMessage());
    sendResponse('Something went wrong. Please try again later');
  }
}
