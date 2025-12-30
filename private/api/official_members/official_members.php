<?php
include __DIR__ . '/../../includes/helpers.php';

requireLogin();
/* requireCsrf(); */
requireAdmin();

try {
  $db = new Database();
  $method = $_SERVER['REQUEST_METHOD'];
  $action = 'fetchMembers';
  $payload = [];

  if ($method === 'POST') {
    $payload = json_decode(file_get_contents('php://input'), true) ?? [];
    $action = $payload['action'] ?? $action;
    requireCsrf();
  }

  switch ($action) {
    case 'fetchMembers':
      requireAdmin();
      fetchMembers($db);
      break;

    case 'updateMember':
      handleUpdate($payload);
      break;

    case 'deleteMember':
      handleDelete($payload);
      break;

    default:
      http_response_code(400);
      sendResponse('Invalid action');
  }
} catch (PDOException $e) {
  error_log("Server error: $e");
  http_response_code(500);
  sendResponse('Server error');
}

function fetchMembers(Database $db)
{
  $conn = $db->getConnection();
  $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
  $limit = 5;
  $offset = ($currentPage - 1) * $limit;

  try {
    $countSql = 'SELECT COUNT(*) AS total
                FROM people AS p
                INNER JOIN official_members AS om
                  ON p.person_id = om.person_id';
    $totalMembers = $db->fetchOne($countSql);
    $totalRows = $totalMembers['total'] ?? 0;
    $totalPages = ceil($totalRows / $limit);

    $sql = 'SELECT
            p.*,
            c.chapter_name
          FROM people AS p
          LEFT JOIN chapters AS c
            ON p.chapter_id = c.chapter_id
          INNER JOIN official_members AS om
            ON p.person_id = om.person_id
          LIMIT ? OFFSET ?';

    $stmt = $conn->prepare($sql);

    $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);

    $stmt->execute();
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($members as &$member) {
      $dateOfBirth = new DateTime($member['date_of_birth']);
      $dateJoined = new DateTime($member['date_joined']);
      $createdAt = new DateTime($member['created_at']);
      $updatedAt = new DateTime($member['updated_at']);

      $member['date_of_birth'] = $dateOfBirth->format('M j, Y');
      $member['date_joined'] = $dateJoined->format('M j, Y');
      $member['created_at'] = $createdAt->format('M j, Y');
      $member['updated_at'] = $updatedAt->format('M j, Y');

      $member['middle_name'] = $member['middle_name'] ?? '';
      $member['sponsor'] = $member['sponsor'] ?? '';
      $member['other_club_affiliation'] = $member['other_club_affiliation'] ?? '';
    }
    unset($member);

    sendResponse('Member records fetched', [
      'members_data' => $members,
      'pagination' => [
        'current_page' => $currentPage,
        'total_pages' => (int)$totalPages,
        'total_records' => (int)$totalRows
      ]
    ], 'success');
  } catch (Throwable $e) {
    error_log("Error fetching members data: $e");
    sendResponse('Something went wrong. Please try again later');
  }
}

function handleUpdate($payload, $db = new Database)
{
  $memberId = $payload['person_id'] ?? null;

  $personId = filter_var(
    $memberId,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
  );

  if ($personId === false) {
    sendResponse('Missing or invalid member ID', [], 'warning');
  }

  $requiredFields = [
    'first_name',
    'last_name',
    'date_of_birth',
    'civil_status',
    'blood_type',
    'home_address',
    'phone_number',
    'email',
    'emergency_contact_name',
    'emergency_contact_number',
    'occupation',
    'license_number',
    'motorcycle_brand',
    'motorcycle_model',
    'chapter_id',
    'date_joined'
  ];

  $optionalFields = ['middle_name', 'sponsor', 'other_club_affiliation'];

  foreach ($requiredFields as $field) {
    if (!isset($payload[$field]) || trim((string)$payload[$field]) === '') {
      sendResponse('Please fill out all required fields correctly', [], 'warning');
    }
  }

  $phoneNumber = $payload['phone_number'];
  $email = $payload['email'];

  $sql = 'SELECT 1 FROM people WHERE phone_number = ? AND person_id != ? LIMIT 1';
  $check = $db->fetchOne($sql, [$phoneNumber, $personId]);

  if ($check) {
    sendResponse('Phone number is already in used', [], 'warning');
  }

  $sql = 'SELECT 1 FROM people WHERE email = ? AND person_id != ? LIMIT 1';
  $check = $db->fetchOne($sql, [$email, $personId]);

  if ($check) {
    sendResponse('Email is already in used', [], 'warning');
  }

  $params = [];

  foreach (array_merge($requiredFields, $optionalFields) as $field) {
    $value = $payload[$field] ?? '';
    $params[":$field"] = is_string($value) ? trim($value) : (string)$value;
  }

  foreach ($optionalFields as $field) {
    if (empty($payload[$field])) {
      $params[":$field"] = '';
    }
  }

  $params[':person_id'] = $personId;

  try {
    $sql = 'UPDATE people SET
              first_name = :first_name,
              last_name = :last_name,
              middle_name = :middle_name,
              date_of_birth = :date_of_birth,
              civil_status = :civil_status,
              blood_type = :blood_type,
              home_address = :home_address,
              phone_number = :phone_number,
              email = :email,
              emergency_contact_name = :emergency_contact_name,
              emergency_contact_number = :emergency_contact_number,
              occupation = :occupation,
              license_number = :license_number,
              motorcycle_brand = :motorcycle_brand,
              motorcycle_model = :motorcycle_model,
              sponsor = :sponsor,
              other_club_affiliation = :other_club_affiliation,
              chapter_id = :chapter_id,
              date_joined = :date_joined
            WHERE person_id = :person_id';
    $update = $db->execute($sql, $params);

    if ($update->rowCount() > 0) {
      sendResponse('Member info successfully updated', [], 'success');
    } else {
      $sql = 'SELECT 1 FROM people WHERE person_id = ? LIMIT 1';
      $check = $db->fetchOne($sql, [$personId]);

      if ($check) {
        sendResponse('No changes were made', [], 'success');
      } else {
        sendResponse('No member found with the ID provided', [], 'warning');
      }
    }
  } catch (PDOException $e) {
    error_log("Database error: $e");
    sendResponse('An error has occured. Please try again later');
  } catch (\Throwable $e) {
    if ($e->getCode() === '23000') {
      sendResponse('An input is already in used', [], 'warning');
    }

    error_log("Error processing membership application: $e");
    sendResponse('An error has occured. Please try again later');
  }
}

function handleDelete($payload, $db = new Database)
{
  $userId = $_SESSION['user_id'] ?? null;
  $personId = $payload['person_id'] ?? null;
  $inputPassword = $payload['password'] ?? null;

  if (!$personId || !$inputPassword || !$userId) {
    http_response_code(422);
    sendResponse('Missing required');
    return;
  }

  try {
    $sql = 'SELECT password FROM users WHERE user_id = :user_id';
    $user = $db->fetchOne($sql, ['user_id' => $userId]);

    if (!password_verify($inputPassword, $user['password'])) {
      sendResponse('Incorrect password', [], 'warning');
      return;
    }

    $sql = 'DELETE FROM people WHERE person_id = :person_id';
    $db->execute($sql, ['person_id' => $personId]);

    sendResponse('Member successfully deleted', [], 'success');
  } catch (\Throwable $e) {
    error_log("Failed to delete member $e");
    sendResponse('Failed to delete member');
  }
}
