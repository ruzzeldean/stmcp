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
  $db->beginTransaction();

  $sql = 'INSERT INTO official_members (
      first_name, last_name, middle_name, date_of_birth, civil_status, blood_type, home_address, phone_number, email, emergency_contact_name, emergency_contact_number, occupation, license_number, motorcycle_brand, motorcycle_model, sponsor, other_club_affiliation, chapter_id, date_joined
    )
    SELECT
      first_name, last_name, middle_name, date_of_birth, civil_status, blood_type, home_address, phone_number, email, emergency_contact_name, emergency_contact_number, occupation, license_number, motorcycle_brand, motorcycle_model, sponsor, other_club_affiliation, chapter_id, date_joined
    FROM aspirants
    WHERE aspirant_id = :aspirant_id
  ';

  $transfer = $db->execute($sql, ['aspirant_id' => $aspirantID]);

  if ($transfer->rowCount() === 0) {
    $db->rollBack();
    sendResponse('error', 'Aspirant not found');
  }

  $sql = 'DELETE FROM aspirants WHERE aspirant_id = :aspirant_id';
  $db->execute($sql, ['aspirant_id' => $aspirantID]);

  $db->commit();
  sendResponse('success', 'Application approved');
} catch (Throwable $e) {
  $db->rollBack();
  error_log('Error: ' . $e);
  sendResponse('error', 'Something went wrong. Please try again later');
}
