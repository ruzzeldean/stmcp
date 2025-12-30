<?php
include __DIR__ . '/../../includes/helpers.php';

requireLogin();
requireModerator();

$userId = $_SESSION['user_id'];

$db = new Database();
$method = $_SERVER['REQUEST_METHOD'];
$action = 'fetchAspirants';
$payload = [];

if ($method === 'POST') {
  requireCsrf();
  $payload = json_decode(file_get_contents('php://input'), true) ?? [];
  $action = $payload['action'] ?? $action;
}

switch ($action) {
  case 'fetchAspirants':
    fetchAspirants();
    break;

  case 'approve':
    handleApprove();
    break;

  case 'reject':
    handleReject();
    break;

  default:
    http_response_code(400);
    sendResponse('Invalid action');
}

function fetchAspirants($db = new Database)
{
  $conn = $db->getConnection();
  $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
  $limit = 5;
  $offset = ($currentPage - 1) * $limit;

  try {
    $countSql = 'SELECT COUNT(*) AS total
                FROM people AS p
                INNER JOIN aspirants AS a
                  ON p.person_id = a.person_id';
    $totalMyDonations = $db->fetchOne($countSql);
    $totalRows = $totalMyDonations['total'] ?? 0;
    $totalPages = ceil($totalRows / $limit);

    $sql = 'SELECT
              p.*,
              c.chapter_name
            FROM people AS p
            LEFT JOIN chapters AS c
              ON p.chapter_id = c.chapter_id
            INNER JOIN aspirants AS a
              ON p.person_id = a.person_id
            LIMIT ? OFFSET ?';
    $stmt = $conn->prepare($sql);

    $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);

    $stmt->execute();
    $aspirants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($aspirants as &$aspirant) {
      $dateOfBirth = new DateTime($aspirant['date_of_birth']);
      $dateJoined = new DateTime($aspirant['date_joined']);
      $createdAt = new DateTime($aspirant['created_at']);
      $updatedAt = new DateTime($aspirant['updated_at']);

      $aspirant['date_of_birth'] = $dateOfBirth->format('M j, Y');
      $aspirant['date_joined'] = $dateJoined->format('M j, Y');
      $aspirant['created_at'] = $createdAt->format('M j, Y');
      $aspirant['updated_at'] = $updatedAt->format('M j, Y');

      $aspirant['middle_name'] = $aspirant['middle_name'] ?? '';
      $aspirant['sponsor'] = $aspirant['sponsor'] ?? '';
      $aspirant['other_club_affiliation'] = $aspirant['other_club_affiliation'] ?? '';
    }
    unset($aspirant);

    sendResponse('Aspirants list fetched', [
      'aspirants_list' => $aspirants,
      'pagination' => [
        'current_page' => $currentPage,
        'total_pages' => (int)$totalPages,
        'total_records' => (int)$totalRows
      ]
    ], 'success');
  } catch (\Throwable $e) {
    //throw $th;
  }


  http_response_code(200);
  sendResponse('Aspirant records fetched', $aspirants, 'success');
}

function handleApprove($db = new Database)
{
  $payload = json_decode(file_get_contents('php://input'), true) ?? [];
  $personId = $payload['person_id'] ?? null;
  $inputPassword = $payload['password'] ?? null;
  $userId = $_SESSION['user_id'] ?? null;

  if (!$personId) {
    // http_response_code(422);
    sendResponse('Missing aspirant ID');
  }

  if (!$inputPassword) {
    sendResponse('Password is required');
  }

  try {
    $user = $db->fetchOne('SELECT password FROM users WHERE user_id = ?', [$userId]);
    if (!password_verify($inputPassword, $user['password'])) {
      // http_response_code(401);
      sendResponse('Incorrect password');
      return;
    }

    $db->beginTransaction();

    $sql = 'SELECT first_name, last_name, email FROM people WHERE person_id = ?
            LIMIT 1';
    $aspirant = $db->fetchOne($sql, [$personId]);

    if (!$aspirant) {
      sendResponse('Aspirant not found');
    }

    $recipientName = $aspirant['first_name'] . ' ' . $aspirant['last_name'];
    $recipientEmail = $aspirant['email'];

    $db->execute(
      'INSERT INTO official_members (person_id) VALUES (?)',
      [$personId]
    );

    $db->execute('DELETE FROM aspirants WHERE person_id = ?', [$personId]);

    $db->execute(
      'UPDATE users SET role_id = 4 WHERE person_id = ?',
      [$personId]
    );

    $db->commit();

    require __DIR__ . '/send_email.php';

    sendResponse('Aspirant successfully approved', [], 'success');
  } catch (\Throwable $e) {
    error_log("Error approving aspirant $e");
    sendResponse('Failed to approve aspirant');
  }
}

function handleReject($db = new Database)
{
  $payload = json_decode(file_get_contents('php://input'), true) ?? [];
  $personId = $payload['person_id'] ?? null;
  $inputPassword = $payload['password'] ?? null;
  $userId = $_SESSION['user_id'] ?? null;

  if (!$personId) {
    // http_response_code(422);
    sendResponse('Missing aspirant ID');
  }

  if (!$inputPassword) {
    sendResponse('Password is required');
  }

  try {
    $user = $db->fetchOne('SELECT password FROM users WHERE user_id = ?', [$userId]);
    if (!password_verify($inputPassword, $user['password'])) {
      // http_response_code(401);
      sendResponse('Incorrect password');
      return;
    }

    $db->execute('DELETE FROM people WHERE person_id = ?', [$personId]);

    sendResponse('Aspirant successfully deleted', [], 'success');
  } catch (\Throwable $e) {
    error_log("Error rejecting aspirant $e");
    sendResponse('Failed to reject aspirant');
  }
}
