<?php
require_once __DIR__ . '/../../includes/helpers.php';

requireLogin();
requirePost();
requireCsrf();

$db = new Database();
$data = [];

if (empty($_POST['purpose'])) {
  sendResponse('error', 'Donation purpose is required');
}

if (!validateDate($_POST['start_date'])) {
  sendResponse('error', 'Start date is required');
}

if (!validateDate($_POST['due_date'])) {
  sendResponse('error', 'Due date is required');
}

$data['purpose'] = trim($_POST['purpose']);
$data['description'] = trim($_POST['description']) ?? '';
$data['created_by'] = $_SESSION['user_id'];
$data['status'] = $_SESSION['role_id'] === 2 ? 'Active'  : 'Pending';
$data['start_date'] = $_POST['start_date'];
$data['due_date'] = $_POST['due_date'];

try {
  $sql = 'INSERT INTO donations (purpose, description, created_by, status, start_date, due_date)
          VALUES (:purpose, :description, :created_by, :status, :start_date, :due_date)';
  $db->execute($sql, $data);

  sendResponse('success', 'Donation created successfully');
} catch (Throwable $e) {
  error_log('Error creating a donation: ' . $e);
  sendResponse('error', 'An error has occured. Please try again later');
}
