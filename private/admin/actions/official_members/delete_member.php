<?php
require_once __DIR__ . '/../../../includes/helpers.php';

requireLogin();
requirePost();
requireCsrf();

if (!isset($_POST['memberID'])) {
  sendResponse('error', 'Invalid Member ID');
}

$memberID = (int) $_POST['memberID'];

try {
  $db = new Database();
/* $db->beginTransaction();

  $sql = 'DELETE FROM official_members WHERE member_id = :member_id';
  $stmt = $db->execute($sql, ['member_id' => $memberID]);

  if (!$stmt) {
    $db->rollBack();
    sendResponse('error', 'An error has occured. Please try again later');
  }

  $sql = 'DELETE FROM users WHERE member_id = :member_id';
  $stmt = $db->execute($sql, ['member_id' => $memberID]);

  if (!$stmt) {
    $db->rollBack();
    sendResponse('error', 'An error has occured. Please try again later');
  }

  $db->commit(); */



  // sendResponse('success', 'Member deleted successfully');
} catch (Throwable $e) {
  /* if ($db->inTransaction()) {
    $db->rollBack();
  } */
  error_log('Failed deleting member: ' . $e);
  sendResponse('error', 'An error has occured. Please try again later');
}
