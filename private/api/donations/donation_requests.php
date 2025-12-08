<?php
require_once __DIR__ . '/../../includes/helpers.php';

requireLogin();
requireAdmin();

$json_data = file_get_contents('php://input');
$request_data = json_decode($json_data, true);

if ($request_data === null || !isset($request_data['action'], $request_data['payload'])) {
  sendResponse('error', 'Invalid or missing JSON data');
}

$action = $request_data['action'];
$payload = $request_data['payload'];
$donation_id = $payload['donation_id'];
$approver = $_SESSION['user_id'] ?? '';

if (!isset($donation_id)) {
  sendResponse('error', 'Missing donation');
}

try {
  switch ($action) {
    case 'approve':
      updateDonation($donation_id, $approver, 'Active');
      sendResponse('success', 'Donation successfully approved');
      break;

    case 'reject':
      updateDonation($donation_id, $approver, 'Rejected');
      sendResponse('success', 'Donation successfully rejected');
      break;
  }
} catch (Throwable $e) {
  error_log("Error handling donation request: $e");
  sendResponse('error', 'Something went wrong. Please try again later');
}

function updateDonation($id, $approver, $status)
{
  $db = new Database();

  $sql = 'UPDATE donations
          SET status = ?, approved_by = ?
          WHERE donation_id = ?';
  $db->execute($sql, [$status, $approver, $id]);
}
