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

if (!isset($_POST['postID']) || !is_numeric($_POST['postID'])) {
  sendResponse('error', 'Invalid Post ID');
}

$postID = (int) $_POST['postID'];

if (!isset($_POST['title']) || !isset($_POST['category']) || !isset($_POST['content'])) {
  sendResponse('error', 'Missing field');
}

$title = trim($_POST['title']);
$category = trim($_POST['category']);
$content = trim($_POST['content']);

if (mb_strlen($title) < 1 || mb_strlen($title) > 100) {
  sendResponse('error', 'Title must be between 1 and 100 characters');
}
if (mb_strlen($content) < 1 || mb_strlen($content) > 5000) {
  sendResponse('error', 'Content must be between 1 and 5000 characters');
}

$allowedCategories = ['Upcoming', 'Past Event'];
if (!in_array($category, $allowedCategories)) {
  sendResponse('error', 'Invalid category');
}

$imagePath = null;

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
  $allowedTypes = ['image/jpeg', 'image/png'];
  $fileType = mime_content_type($_FILES['image']['tmp_name']);
  $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
  $validExtensions = ['jpg', 'jpeg', 'png'];

  if (!in_array($fileType, $allowedTypes) || !in_array($extension, $validExtensions)) {
    sendResponse('error', 'Invalid image type');
  }

  if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
    sendResponse('error', 'Image is too large. Max allowed: 5MB');
  }

  $imageInfo = @getimagesize($_FILES['image']['tmp_name']);
  if ($imageInfo === false) {
    sendResponse('error', 'Uploaded file is not a valid image');
  }

  $newFileName = uniqid('post_', true) . '.' . $extension;
  $uploadDir = __DIR__ . '/../../../../uploads/posts/';
  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
  }

  $destination = $uploadDir . $newFileName;
  if (!move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
    sendResponse('error', 'Failed to upload image');
  }

  $imagePath = $newFileName;
}

if ($imagePath) {
  $stmt = $conn->prepare('SELECT image_path FROM posts WHERE post_id = :post_id');
  $stmt->execute(['post_id' => $postID]);
  $oldImage = $stmt->fetchColumn();

  if ($oldImage) {
    $oldImagePath = __DIR__ . '/../../../../uploads/posts/' . $oldImage;
    if (is_file($oldImagePath)) {
      @unlink($oldImagePath);
    }
  }
}

try {
  if ($imagePath) {
    $sql = 'UPDATE posts SET title = :title, category = :category, content = :content, image_path = :image WHERE post_id = :post_id';
    $params = [
      'title' => $title,
      'category' => $category,
      'content' => $content,
      'image' => $imagePath,
      'post_id' => $postID
    ];
  } else {
    $sql = 'UPDATE posts SET title = :title, category = :category, content = :content WHERE post_id = :post_id';
    $params = [
      'title' => $title,
      'category' => $category,
      'content' => $content,
      'post_id' => $postID
    ];
  }

  $update = $conn->prepare($sql);
  $update->execute($params);

  sendResponse('success', 'Post updated successfully');
} catch (Throwable $ex) {
  error_log('Update post error: ' . $ex->getMessage());
  sendResponse('error', 'Failed to update post. Please try again later');
}
