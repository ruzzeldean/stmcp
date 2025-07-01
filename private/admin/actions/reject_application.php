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

    $deleteSql = 'DELETE FROM aspirants WHERE aspirant_id = :aspirant_id';
    $deleteRecord = $conn->prepare($deleteSql);
    $deleteRecord->execute(['aspirant_id' => $aspirantID]);

    if ($deleteRecord->rowCount() === 0) {
      $conn->rollBack();
      echo json_encode([
        'status' => 'error',
        'message' => 'Aspirant not found'
      ]);
      exit;
    }

    $conn->commit();
    echo json_encode([
      'status' => 'success',
      'message' => 'Application rejected'
    ]);
  } catch (Throwable $ex) {
    $conn->rollBack();
    error_log("Error: " . $ex->getMessage());
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
