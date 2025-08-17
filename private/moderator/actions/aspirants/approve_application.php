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

if (!isset($_POST['aspirantID'])) {
  sendResponse('error', 'Invalid Aspirant ID');
}

$aspirantID = (int) $_POST['aspirantID'];

try {
  $conn->beginTransaction();

  $insertSql = 'INSERT INTO official_members (
      first_name, last_name, middle_name, date_of_birth, civil_status, blood_type, home_address, phone_number, email, emergency_contact_name, emergency_contact_number, occupation, license_number, motorcycle_brand, motorcycle_model, sponsor, other_club_affiliation, chapter_id, date_joined
    )
    SELECT
      first_name, last_name, middle_name, date_of_birth, civil_status, blood_type, home_address, phone_number, email, emergency_contact_name, emergency_contact_number, occupation, license_number, motorcycle_brand, motorcycle_model, sponsor, other_club_affiliation, chapter_id, date_joined
    FROM aspirants
    WHERE aspirant_id = :aspirant_id
  ';

  $transfer = $conn->prepare($insertSql);
  $transfer->execute(['aspirant_id' => $aspirantID]);

  if ($transfer->rowCount() === 0) {
    $conn->rollBack();
    sendResponse('error', 'Aspirant not found');
  }

  $deleteSql = 'DELETE FROM aspirants WHERE aspirant_id = :aspirant_id';
  $remove = $conn->prepare($deleteSql);
  $remove->execute(['aspirant_id' => $aspirantID]);

  $conn->commit();
  sendResponse('success', 'Application approved');
} catch (Throwable $ex) {
  $conn->rollBack();
  error_log('Error: ' . $ex);
  sendResponse('error', 'Something went wrong. Please try again later');
}
