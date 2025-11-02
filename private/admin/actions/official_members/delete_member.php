<?php
require_once __DIR__ . '/../../../includes/helpers.php';

requireLogin();
requirePost();
requireCsrf();

if (!isset($_POST['memberID'])) {
  sendResponse('error', 'Invalid Member ID');
}

$memberID = (int) $_POST['memberID'];

$db = new Database();

try {
  $sql = 'DELETE FROM official_members WHERE member_id = :member_id';

  $db->execute($sql, ['member_id' => $memberID]);

  sendResponse('success', 'Member deleted successfully');
} catch (Throwable $e) {
  error_log('Failed deleting member: ' . $e->getMessage());
  sendResponse('error', 'An error has occured. Please try again later');
}
