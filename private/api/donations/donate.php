<?php
require_once __DIR__ . '/../../includes/helpers.php';

requireLogin();
requirePost();
requireCsrf();

$userId = (int) $_SESSION['user_id'];

if (empty($_POST['donation_id'])) {
  sendResponse('error', 'Donation ID is required');
}

$donationId = (int) $_POST['donation_id'];

if (empty($_POST['amount'])) {
  sendResponse('error', 'Amount is required');
}

$amount = (float) $_POST['amount'];

$uploadDir = '../../uploads/receipts/';
$allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
$MAX_FILE_SIZE = 5 * 1024 * 1024;

if (!is_dir($uploadDir)) {
  mkdir($uploadDir, 0755, true);
}

if (!isset($_FILES['image'])) {
  sendResponse('error', 'No image uploaded');
}

$image = $_FILES['image'];

if ($image['error'] !== UPLOAD_ERR_OK) {
  sendResponse('error', 'Upload error');
}

if ($image['size'] > $MAX_FILE_SIZE) {
  sendResponse('error', 'File too large (max 5 MB)');
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $image['tmp_name']);

if (!in_array($mime, $allowedTypes)) {
  sendResponse('error', 'Only JPEG/JPG and PNG are allowed');
}

$imageInfo = getimagesize($image['tmp_name']);
if ($imageInfo === false) {
  sendResponse('error', 'Invalid image file');
}

$extension = pathinfo($image['name'], PATHINFO_EXTENSION);
$extension = strtolower($extension);
$filename = 'receipt_' . bin2hex(random_bytes(16)) . '.' . $extension;
$filepath = $uploadDir . $filename;

if (!move_uploaded_file($image['tmp_name'], $filepath)) {
  sendResponse('error', 'Failed to save image');
}

try {
  $db = new Database();

  $sql = 'SELECT donation_id FROM donations
          WHERE donation_id = :donation_id
            AND status = "Active"
          LIMIT 1';
  $donation = $db->fetchOne($sql, ['donation_id' => $donationId]);

  if (!$donation) {
    unlink($filepath);
    sendResponse('error', 'Donation not found or already closed');
  }

  $sql = 'INSERT INTO donors (donation_id, user_id, proof_image, amount)
          VALUES (:donation_id, :user_id, :proof_image, :amount)';

  $data = [
    'donation_id' => $donationId,
    'user_id' => $userId,
    'proof_image' => $filename,
    'amount' => $amount
  ];

  $db->execute($sql, $data);

  sendResponse('success', 'Thank you! Your donation has been recorded');
} catch (PDOException $e) {
  if ($e->getCode() === '23000') {
    if (isset($filepath) && file_exists($filepath)) {
      unlink($filepath);
    }
    sendResponse('error', 'You have already donated to this campaign');
  }
} catch (Throwable $e) {
  if (isset($filepath) && file_exists($filepath)) {
    unlink($filepath);
  }
  error_log("Donation error: $e");
  sendResponse('error', 'An error has occured. Please try again later');
}
