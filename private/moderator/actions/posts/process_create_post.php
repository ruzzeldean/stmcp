<?php
require_once __DIR__ . '/../../../includes/helpers.php';

requireLogin();
requirePost();
requireCsrf();

$db = new Database();
$moderatorID = $_SESSION['user_id'];

$maxTitleLength = 100;
$maxContentLength = 5000;

$title = htmlspecialchars(trim($_POST['title'] ?? ''), ENT_QUOTES, 'UTF-8');
$category = htmlspecialchars(trim($_POST['category'] ?? ''), ENT_QUOTES, 'UTF-8');
$content = trim($_POST['content'] ?? '');

if (empty($title) || empty($category) || empty($content)) {
  sendResponse('error', 'All fields are required');
}

if (strlen($title) > $maxTitleLength) {
  sendResponse('error', 'Title must be less than ' . $maxTitleLength . ' characters');
}

if (strlen($content) > $maxContentLength) {
  sendResponse('error', 'Content must be less that ' . $maxContentLength . ' characters');
}

$validCategories = ['Announcement', 'Upcoming', 'Past Event'];
if (!in_array($category, $validCategories)) {
  sendResponse('error', 'Invalid category');
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
  sendResponse('error', 'Image upload failed');
}

$maxFileSize = 5 * 1024 * 1024;
if ($_FILES['image']['size'] > $maxFileSize) {
  sendResponse('error', 'Image is too large. Max allowed is 5MB');
}

$imageTmpPath = $_FILES['image']['tmp_name'];
$imageName = basename($_FILES['image']['name']);
$imageExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
$allowedExtensions = ['jpg', 'jpeg', 'png'];
$allowedMimeType = ['image/jpeg', 'image/png'];
$mimeType = mime_content_type($imageTmpPath);

if (!in_array($imageExtension, $allowedExtensions) || !in_array($mimeType, $allowedMimeType)) {
  sendResponse('error', 'Invalid image format or type');
}

if (!getimagesize($imageTmpPath)) {
  sendResponse('error', 'File is not a valid image');
}

$newImageName = uniqid('post_', true) . '.' . $imageExtension;
$uploadDirectory = __DIR__ . '/../../../../uploads/posts/';

if (!is_dir($uploadDirectory)) {
  if (!mkdir($uploadDirectory, 0755, true)) {
    error_log('Failed to create upload directory');
    sendResponse('error', 'Failed to create upload directory');
  }
}

$uploadPath = realpath($uploadDirectory) . DIRECTORY_SEPARATOR . $newImageName;
if ($uploadPath === false) {
  sendResponse('error', 'Invalid file path');
}

if (!move_uploaded_file($imageTmpPath, $uploadPath)) {
  sendResponse('error', 'Failed to move uploaded file');
}

try {
  $sql = 'INSERT INTO posts (title, category, image_path, status, content, created_by)
          VALUES (:title, :category, :image_path, :status, :content, :moderator_id)';
  $params = [
    'title' => $title,
    'category' => $category,
    'image_path' => $newImageName,
    'content' => $content,
    'status' => 'Pending',
    'moderator_id' => $moderatorID
  ];

  $db->execute($sql, $params);

  sendResponse('success', 'Post created successfully');
} catch (Throwable $e) {
  error_log('Create Post Error: ' . $e->getMessage());
  sendResponse('error', 'An unexpected error occured while createing the post');
}
