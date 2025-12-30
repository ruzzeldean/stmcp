<?php

declare(strict_types=1);

include __DIR__ . '/../../includes/helpers.php';

requireLogin();
requireAdminModerator();
requirePost();

$userId = (int) $_SESSION['user_id'];
$roleId = (int) $_SESSION['role_id'];

try {
  $db = new Database();
  $action = $_POST['action'] ?? '';

  switch ($action) {
    case 'createPost':
      createPost($userId, $roleId, $db);
      break;

    case 'updatePost':
      updatePost($db);
      break;

    default:
      http_response_code(400);
      sendResponse('Invalid action');
  }
} catch (PDOException $e) {
  error_log('Database connection error: ' . $e->getMessage());
  http_response_code(500);
  sendResponse('Server error');
}

/**
 * Validate and move uploaded image, return relative path on success.
 *
 * @param array  $file      The $_FILES['image'] array
 * @param string $baseDir   Absolute base directory where uploads are stored (with trailing slash)
 * @param int    $maxBytes  Max allowed file size in bytes
 * @return string           Relative path (e.g. uploads/posts/2025/12/filename.png)
 * @throws RuntimeException on validation failure
 */
function handleImageUpload(array $file, string $baseDir, int $maxBytes): string
{
  // Allowed MIME -> extension mapping (do not trust client filename)
  $mimeToExt = [
    'image/jpeg' => 'jpg',
    'image/jpg'  => 'jpg',
    'image/png'  => 'png',
  ];

  if ($file['error'] !== UPLOAD_ERR_OK) {
    throw new RuntimeException('Upload error');
  }

  if ($file['size'] > $maxBytes) {
    throw new RuntimeException('File too large (max ' . ($maxBytes / (1024 * 1024)) . ' MB)');
  }

  if (!is_uploaded_file($file['tmp_name'])) {
    throw new RuntimeException('Invalid upload');
  }

  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  if ($finfo === false) {
    throw new RuntimeException('Server error');
  }

  $mime = finfo_file($finfo, $file['tmp_name']);

  if (!isset($mimeToExt[$mime])) {
    throw new RuntimeException('Only JPEG/JPG and PNG are allowed');
  }

  // Extra check: ensure it's an actual image
  $imageInfo = @getimagesize($file['tmp_name']);
  if ($imageInfo === false) {
    throw new RuntimeException('Invalid image file');
  }

  // Build directories and unique filename
  $subdir = 'uploads/posts/' . date('Y/m') . '/';
  $ext = $mimeToExt[$mime];
  $filename = 'post_' . bin2hex(random_bytes(16)) . '.' . $ext;

  $absDir = rtrim($baseDir, '/') . '/' . $subdir;
  if (!is_dir($absDir) && !mkdir($absDir, 0755, true) && !is_dir($absDir)) {
    throw new RuntimeException('Failed to create upload directory');
  }

  $absPath = $absDir . $filename;
  // Use move_uploaded_file for security
  if (!move_uploaded_file($file['tmp_name'], $absPath)) {
    throw new RuntimeException('Failed to save image');
  }

  // Return relative path used by application (without 'public/assets/' prefix)
  return $subdir . $filename;
}

/**
 * Create a post (supports image upload).
 */
function createPost(int $userId, int $roleId, Database $db): void
{
  $maxTitleLength = 100;
  $maxContentLength = 3000;
  $MAX_FILE_SIZE = 5 * 1024 * 1024; // 5 MB

  $title = trim($_POST['title'] ?? '');
  $category = trim($_POST['category'] ?? '');
  $content = trim($_POST['content'] ?? '');

  if ($title === '' || $category === '' || $content === '') {
    http_response_code(422);
    sendResponse('All fields are required');
  }

  if (mb_strlen($title) > $maxTitleLength) {
    http_response_code(422);
    sendResponse('Title must be less than ' . $maxTitleLength . ' characters');
  }

  if (mb_strlen($content) > $maxContentLength) {
    http_response_code(422);
    sendResponse('Content must be less than ' . $maxContentLength . ' characters');
  }

  $validCategories = ['Announcement', 'Upcoming', 'Past Event'];
  if (!in_array($category, $validCategories, true)) {
    http_response_code(422);
    sendResponse('Invalid category');
  }

  if (!isset($_FILES['image'])) {
    http_response_code(422);
    sendResponse('No image uploaded');
  }

  try {
    $uploadBase = __DIR__ . '/../../../public/assets'; // absolute path to public/assets
    $relativePath = handleImageUpload($_FILES['image'], $uploadBase, $MAX_FILE_SIZE);
  } catch (Throwable $e) {
    error_log('Image upload error (create): ' . $e->getMessage());
    http_response_code(422);
    sendResponse($e->getMessage());
  }

  try {
    $authorized = in_array($roleId, [1, 2], true);
    $status = $authorized ? 'Published' : 'Pending';

    $sql = 'INSERT INTO posts (title, category, image_path, content, status, created_by)
                VALUES (:title, :category, :image_path, :content, :status, :created_by)';
    $params = [
      ':title'      => $title,
      ':category'   => $category,
      ':image_path' => $relativePath,
      ':content'    => $content,
      ':status'     => $status,
      ':created_by' => $userId,
    ];

    $db->execute($sql, $params);

    sendResponse('Post created successfully', [], 'success');
  } catch (Throwable $e) {
    error_log('Error creating post: ' . $e->getMessage());
    http_response_code(500);
    sendResponse('Failed to create post');
  }
}

/**
 * Update a post. If image uploaded, replace and delete old file.
 */
function updatePost(Database $db): void
{
  $maxTitleLength = 100;
  $maxContentLength = 500;
  $MAX_FILE_SIZE = 8 * 1024 * 1024; // 8 MB

  $postId = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
  if ($postId === false || $postId === null) {
    http_response_code(422);
    sendResponse('Invalid post ID');
  }

  $title = trim($_POST['title'] ?? '');
  $category = trim($_POST['category'] ?? '');
  $content = trim($_POST['content'] ?? '');

  if ($title === '' || $category === '' || $content === '') {
    http_response_code(422);
    sendResponse('All fields are required');
  }

  if (mb_strlen($title) > $maxTitleLength) {
    http_response_code(422);
    sendResponse('Title must be less than ' . $maxTitleLength . ' characters');
  }

  if (mb_strlen($content) > $maxContentLength) {
    http_response_code(422);
    sendResponse('Content must be less than ' . $maxContentLength . ' characters');
  }

  $validCategories = ['Announcement', 'Upcoming', 'Past Event'];
  if (!in_array($category, $validCategories, true)) {
    http_response_code(422);
    sendResponse('Invalid category');
  }

  $newRelativePath = null;

  // If an image file is provided, validate and store it.
  if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
    try {
      $uploadBase = __DIR__ . '/../../../public/assets';
      $newRelativePath = handleImageUpload($_FILES['image'], $uploadBase, $MAX_FILE_SIZE);
    } catch (Throwable $e) {
      error_log('Image upload error (update): ' . $e->getMessage());
      http_response_code(422);
      sendResponse($e->getMessage());
    }

    // Retrieve old image path to delete it after successful move/upload
    try {
      $sql = 'SELECT image_path FROM posts WHERE post_id = :post_id LIMIT 1';
      $stmt = $db->execute($sql, [':post_id' => $postId]);
      $oldImage = $stmt->fetchColumn();
    } catch (Throwable $e) {
      // If DB read fails, attempt to clean up the uploaded file and return error
      error_log('Failed to fetch old image: ' . $e->getMessage());
      // remove newly uploaded file to avoid orphaning
      @unlink(__DIR__ . '/../../../public/assets/' . $newRelativePath);
      http_response_code(500);
      sendResponse('Failed to update post');
    }
  }

  try {
    if ($newRelativePath !== null) {
      $sql = 'UPDATE posts
                    SET title = :title, category = :category, content = :content, image_path = :image
                    WHERE post_id = :post_id LIMIT 1';
      $params = [
        ':title'    => $title,
        ':category' => $category,
        ':content'  => $content,
        ':image'    => $newRelativePath,
        ':post_id'  => $postId,
      ];
    } else {
      $sql = 'UPDATE posts
                    SET title = :title, category = :category, content = :content
                    WHERE post_id = :post_id LIMIT 1';
      $params = [
        ':title'    => $title,
        ':category' => $category,
        ':content'  => $content,
        ':post_id'  => $postId,
      ];
    }

    $db->execute($sql, $params);

    // If we replaced the image and we had an old image, delete old file
    if (!empty($oldImage) && !empty($newRelativePath)) {
      $oldImagePath = __DIR__ . '/../../../public/assets/' . ltrim($oldImage, '/');
      if (is_file($oldImagePath)) {
        @unlink($oldImagePath); // suppress warnings, already logged when upload succeeded
      }
    }

    sendResponse('Post updated successfully', [], 'success');
  } catch (Throwable $e) {
    // On failure, try to remove newly uploaded file to avoid orphan files
    if (!empty($newRelativePath)) {
      @unlink(__DIR__ . '/../../../public/assets/' . ltrim($newRelativePath, '/'));
    }
    error_log('Error updating post: ' . $e->getMessage());
    http_response_code(500);
    sendResponse('Failed to update post');
  }
}
