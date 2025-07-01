<?php
header('Content-Type: application/json');

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aspirantID'])) {
  $aspirantID = (int)$_POST['aspirantID'];

  require_once __DIR__ . '/../../../config/connection.php';

  try {
    $conn->beginTransaction();

    $insertSql = '
      INSERT INTO official_members (
        first_name, middle_name, last_name, date_of_birth, blood_type, address, phone_number, contact_person_number, email, occupation, drivers_license_number, brand, model, engine_size_cc, sponsored_by, affiliations, team_chapter
      )
      SELECT
        first_name, middle_name, last_name, date_of_birth, blood_type, address, phone_number, contact_person_number, email, occupation, drivers_license_number, brand, model, engine_size_cc, sponsored_by, affiliations, team_chapter
      FROM aspirants
      WHERE aspirant_id = :aspirant_id
    ';

    $transfer = $conn->prepare($insertSql);
    $transfer->execute(['aspirant_id' => $aspirantID]);

    if ($transfer->rowCount() === 0) {
      throw new Exception('No aspirant found with the given ID.');
    }

    $deleteSql = 'DELETE FROM aspirants WHERE aspirant_id = :aspirant_id';
    $remove = $conn->prepare($deleteSql);
    $remove->execute(['aspirant_id' => $aspirantID]);

    $conn->commit();
    echo json_encode([
      'status' => 'success',
      'message' => 'Application approved'
    ]);
  } catch (Throwable $ex) {
    $conn->rollBack();
    error_log("Database error:" . $ex->getMessage());
    echo json_encode([
      'status' => 'error',
      'message' => 'Something went wrong'
    ]);
  }
} else {
  echo json_encode([
    'status' => 'error',
    'message' => 'Invalid request'
  ]);
}
