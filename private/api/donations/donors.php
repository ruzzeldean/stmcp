<?php
require_once __DIR__ . '/../../includes/helpers.php';

if (empty($_GET['donation_id'])) {
  sendResponse('error', 'Missing donation id');
}

$donationId = $_GET['donation_id'];

try {
  $db = new Database();

  $sql = 'SELECT
            d.amount,
            d.proof_image,
            CONCAT(p.first_name, " ", p.last_name) AS donor_name
          FROM donors AS d
          INNER JOIN users AS u
            ON d.user_id = u.user_id
          LEFT JOIN people AS p
            ON p.person_id = u.person_id
          WHERE d.donation_id = :donation_id';
  $donors = $db->fetchAll($sql, ['donation_id' => $donationId]);

  sendResponse('success', 'Donors successfully fetched', $donors);
} catch (Throwable $e) {
  error_log('Error fetching donors: ' . $e);
  sendResponse('error', 'An error has occured. Please try again later');
}