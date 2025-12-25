<?php
include __DIR__ . '/../../includes/helpers.php';

requireLogin();
/* requireCsrf(); */
requireModerator();

$userId = $_SESSION['user_id'];

try {
  $db = new Database();
  $method = $_SERVER['REQUEST_METHOD'];
  $action = 'fetchAspirants';
  $input = [];

  if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?? [];
    $action = $input['action'] ?? $action;
  }

  switch ($action) {
    case 'fetchAspirants':
      fetchAspirants($db);
      break;

    case 'approveAspirant':
      updateMember($db);
      break;

    case 'rejectAspirant':
      deleteMember($db, $input, $userId);
      break;

    default:
      http_response_code(400);
      sendResponse('Invalid action');
  }
} catch (PDOException $e) {
  if ($db->inTransaction()) {
    $db->rollBack();
  }
  error_log("Server error: $e");
  http_response_code(500);
  sendResponse('Server error');
} catch (Throwable $e) {
  if ($db->inTransaction()) {
    $db->rollBack();
  }
  error_log("Login error: $e");
  http_response_code(500);
  sendResponse('Server error');
}

function fetchAspirants(Database $db)
{
  $sql = 'SELECT
            p.*,
            c.chapter_name
          FROM people AS p
          LEFT JOIN chapters AS c
            ON p.chapter_id = c.chapter_id
          INNER JOIN aspirants AS a
            ON p.person_id = a.person_id';
  $aspirants = $db->fetchAll($sql);

  http_response_code(200);
  sendResponse('Aspirant records fetched', $aspirants);
}

function updateMember(Database $db)
{
  http_response_code(200);
  sendResponse('Member updated successfully');
}

function deleteMember(Database $db, $input, $userId)
{
  $personId = $input['person_id'] ?? null;
  $inputPassword = $input['password'] ?? null;

  if (!$personId || !$inputPassword || !$userId) {
    http_response_code(422);
    sendResponse($personId);
    return;
  }

  $sql = 'SELECT password FROM users WHERE user_id = :user_id';
  $user = $db->fetchOne($sql, ['user_id' => $userId]);

  if (!password_verify($inputPassword, $user['password'])) {
    http_response_code(401);
    sendResponse('Incorrect password');
    return;
  }

  $db->beginTransaction();

  $sql = 'DELETE FROM people WHERE person_id = :person_id';
  $stmt = $db->execute($sql, ['person_id' => $personId]);

  if (!$stmt) {
    $db->rollBack();
    sendResponse('error', 'An error has occured. Please try again later');
  }

  $db->commit();

  http_response_code(200);
  sendResponse('Member successfully deleted');
}
