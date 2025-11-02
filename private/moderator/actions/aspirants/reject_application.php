<?php
require_once __DIR__ . '/../../../includes/helpers.php';

requireLogin();
requirePost();
requireCsrf();

if (!isset($_POST['aspirantID'])) {
  sendResponse('error', 'Invalid Aspirant ID');
}

$aspirantID = (int) $_POST['aspirantID'];
$db = new Database();

try {
  $sql = 'DELETE FROM aspirants WHERE aspirant_id = :aspirant_id';

  $reject = $db->execute($sql, ['aspirant_id' => $aspirantID]);

  if ($reject->rowCount() === 0) {
    sendResponse('error', 'Aspirant not found');
  }

  sendResponse('success', 'Application rejected');
} catch (Throwable $e) {
  error_log('Error: ' . $e->getMessage());
  sendResponse('error', 'Something went wrong. Please try again later');
}
