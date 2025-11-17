<?php
require_once __DIR__ . '/../../../includes/helpers.php';

requireLogin();
requirePost();
requireCsrf();

if (!isset($_POST['aspirant_id'])) {
  sendResponse('error', 'Invalid Aspirant ID');
}

$aspirantId = (int) $_POST['aspirant_id'];
$db = new Database();

try {
  $db->beginTransaction();

  $sql = 'SELECT first_name, last_name, email
          FROM people
          WHERE person_id = :person_id
          LIMIT 1';
  $aspirant = $db->fetchOne($sql, ['person_id' => $aspirantId]);

  if (!$aspirant) {
    sendResponse('error', 'Aspirant not found');
  }

  $recipientName = $aspirant['first_name'] . ' ' . $aspirant['last_name'];
  $recipientEmail = $aspirant['email'];

  $sql = 'INSERT INTO official_members (person_id)
          VALUES (:person_id)';
  $db->execute($sql, ['person_id' => $aspirantId]);

  $sql = 'DELETE FROM aspirants
          WHERE person_id = :person_id';
  $db->execute($sql, ['person_id' => $aspirantId]);

  $sql = 'UPDATE users
          SET role_id = 4
          WHERE person_id = :person_id';
  $db->execute($sql, ['person_id' => $aspirantId]);

  $db->commit();

  require __DIR__ . '/send_email.php';

  sendResponse('success', 'Application approved');
} catch (Throwable $e) {
  if ($db->inTransaction()) {
    $db->rollBack();
  }
  error_log('Error: ' . $e);
  sendResponse('error', 'Something went wrong. Please try again later');
}
