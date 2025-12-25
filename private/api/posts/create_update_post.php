<?php
include __DIR__ . '/../../includes/helpers.php';

requireLogin();
requireAdminModerator();
requirePost();

$userId = $_SESSION['user_id'];
$roleId = (int) $_SESSION['role_id'];

try {
  $db = new Database();
  $action = $_POST['action'];

  switch ($action) {
    case 'createPost':
      createPost($userId, $roleId);
      break;

    case 'updatePost':
      updatePost();
      break;
  }
} catch (PDOException $e) {
  error_log("Database error: $e");
}

function createPost($userId, $roleId, $db = new Database)
{
  /*  */
  $maxTitleLength = 100;
  $maxContentLength = 500;

  // isset or empty?
  $title = trim($_POST['title'] ?? '');
  $category = trim($_POST['category'] ?? '');
  $content = trim($_POST['content'] ?? '');

  if (empty($title) || empty($category) || empty($content)) {
    sendResponse('All fields are required', [], 'error');
  }

  if (strlen($title) > $maxTitleLength) {
    sendResponse('Title must be less than ' . $maxTitleLength . ' characters', [], 'error');
  }

  if (strlen($content) > $maxContentLength) {
    sendResponse('Content must be less than ' . $maxContentLength . ' characters', [], 'error');
  }

  $validCategories = ['Announcement', 'Upcoming', 'Past Event'];
  if (!in_array($category, $validCategories)) {
    sendResponse('Invalid category', [], 'error');
  }
  /*  */

  $uploadDir = '../../storage/uploads/posts/';
  $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
  $MAX_FILE_SIZE = 5 * 1024 * 1024;

  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
  }

  if (!isset($_FILES['image'])) {
    http_response_code(422);
    sendResponse('No image uploaded', [], 'error',);
  }

  $image = $_FILES['image'];

  if ($image['error'] !== UPLOAD_ERR_OK) {
    http_response_code(422);
    sendResponse('Upload error', [], 'error',);
  }

  if ($image['size'] > $MAX_FILE_SIZE) {
    http_response_code(422);
    sendResponse('File too large (max 5 MB)', [], 'error',);
  }

  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $mime = finfo_file($finfo, $image['tmp_name']);

  if (!in_array($mime, $allowedTypes)) {
    http_response_code(422);
    sendResponse('Only JPEG/JPG and PNG are allowed', [], 'error',);
  }

  $imageInfo = getimagesize($image['tmp_name']);
  if ($imageInfo === false) {
    http_response_code(422);
    sendResponse('Invalid image file', 'error',);
  }

  $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
  $extension = strtolower($extension);
  $filename = 'post_' . bin2hex(random_bytes(16)) . '.' . $extension;
  $filepath = $uploadDir . $filename;

  if (!move_uploaded_file($image['tmp_name'], $filepath)) {
    http_response_code(422);
    sendResponse('Failed to save image', [], 'error',);
  }

  try {
    $authorized = in_array($roleId, [1, 2], true);
    $status = $authorized ? 'Published' : 'Pending';

    $sql = 'INSERT INTO posts (title, category, image_path, status, content, created_by)
          VALUES (:title, :category, :image_path, :status, :content, :created_by)';
    $params = [
      'title' => $title,
      'category' => $category,
      'image_path' => $filename,
      'content' => $content,
      'status' => $status,
      'created_by' => $userId
    ];

    $db->execute($sql, $params);

    sendResponse('Post created successfully');
  } catch (Throwable $e) {
    error_log("Error creating post $e");
  }
}

function updatePost($db = new Database)
{
  $maxTitleLength = 100;
  $maxContentLength = 500;

  // isset or empty?
  $postId = trim($_POST['post_id'] ?? '');
  $title = trim($_POST['title'] ?? '');
  $category = trim($_POST['category'] ?? '');
  $content = trim($_POST['content'] ?? '');

  if (empty($postId)) {
    http_response_code(422);
    sendResponse('MIssing post ID', [], 'error');
  }

  if (empty($title) || empty($category) || empty($content)) {
    sendResponse('All fields are required', [], 'error');
  }

  if (strlen($title) > $maxTitleLength) {
    sendResponse('Title must be less than ' . $maxTitleLength . ' characters', [], 'error');
  }

  if (strlen($content) > $maxContentLength) {
    sendResponse('Content must be less than ' . $maxContentLength . ' characters', [], 'error');
  }

  $validCategories = ['Announcement', 'Upcoming', 'Past Event'];
  if (!in_array($category, $validCategories)) {
    sendResponse('Invalid category', [], 'error');
  }
  /*  */

  $uploadDir = '../../storage/uploads/posts/';
  $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
  $MAX_FILE_SIZE = 8 * 1024 * 1024;

  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
  }

  $filename = '';

  if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
    $image = $_FILES['image'];

    if ($image['error'] !== UPLOAD_ERR_OK) {
      http_response_code(422);
      sendResponse('Upload error', [], 'error',);
    }

    if ($image['size'] > $MAX_FILE_SIZE) {
      http_response_code(422);
      sendResponse('File too large (max 5 MB)', [], 'error',);
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $image['tmp_name']);

    if (!in_array($mime, $allowedTypes)) {
      http_response_code(422);
      sendResponse('Only JPEG/JPG and PNG are allowed', [], 'error',);
    }

    $imageInfo = getimagesize($image['tmp_name']);
    if ($imageInfo === false) {
      http_response_code(422);
      sendResponse('Invalid image file', 'error',);
    }

    $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
    $extension = strtolower($extension);
    $filename = 'post_' . bin2hex(random_bytes(16)) . '.' . $extension;
    $filepath = $uploadDir . $filename;

    if (!move_uploaded_file($image['tmp_name'], $filepath)) {
      http_response_code(422);
      sendResponse('Failed to save image', [], 'error',);
    }

    $sql = 'SELECT image_path FROM posts WHERE post_id = ? LIMIT 1';
    $stmt = $db->execute($sql, [$postId]);
    $oldImage = $stmt->fetchColumn();

    if ($oldImage) {
      $oldImagePath = $uploadDir . $oldImage;
      if (is_file($oldImagePath)) {
        unlink($oldImagePath);
      }
    }
  }

  try {
    if ($filename) {
      $sql = 'UPDATE posts SET title = :title, category = :category, content = :content, image_path = :image WHERE post_id = :post_id LIMIT 1';
      $params = [
        'title' => $title,
        'category' => $category,
        'content' => $content,
        'image' => $filename,
        'post_id' => $postId
      ];
    } else {
      $sql = 'UPDATE posts SET title = :title, category = :category, content = :content WHERE post_id = :post_id';
      $params = [
        'title' => $title,
        'category' => $category,
        'content' => $content,
        'post_id' => $postId
      ];
    }

    $db->execute($sql, $params);

    sendResponse('Post updated successfully');
  } catch (Throwable $e) {
    error_log("Error creating post $e");
  }
}
