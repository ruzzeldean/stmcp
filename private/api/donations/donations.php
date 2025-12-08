<?php
require_once __DIR__ . '/../../includes/helpers.php';

$userId = $_SESSION['user_id'] ?? '';

try {
  $db = new Database();

  $activeCount = 'SELECT COUNT(*) AS total_active
                  FROM donations
                  WHERE status = "Active"';
  $totalActive = $db->fetchOne($activeCount);

  $closedCount = 'SELECT COUNT(*) AS total_closed
                  FROM donations
                  WHERE status = "Closed"';
  $totalClosed = $db->fetchOne($closedCount);

  $sql = 'SELECT
            dt.donation_id,
            dt.purpose,
            dt.start_date,
            dt.due_date,
            dt.status,
            dr.amount
          FROM donations AS dt
          INNER JOIN donors AS dr
            ON dt.donation_id = dr.donation_id
          WHERE dr.user_id = :user_id
          ORDER BY dr.created_at DESC';
  $myDonations = $db->fetchAll($sql, ['user_id' => $userId]);

  foreach ($myDonations as &$donation) {
    $start = new DateTime($donation['start_date']);
    $due = new DateTime($donation['due_date']);

    $donation['start_date'] = $start->format('M j, Y');
    $donation['due_date'] = $due->format('M j, Y');
  }
  unset($donation);

  $data = [
    'total_active' => $totalActive['total_active'],
    'total_closed' => $totalClosed['total_closed'],
    'my_donations' => $myDonations
  ];

  sendResponse('success', 'Data successfully fetched', $data);
} catch (Throwable $e) {
  error_log('Error fetching in donation page: ' . $e);
  sendResponse('error', 'Error fetching data. Please try again later');
}
