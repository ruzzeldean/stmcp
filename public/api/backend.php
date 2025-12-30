<?php
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

include __DIR__ . '/../../config/database.php';

$type = $_GET['type'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $payload = json_decode(file_get_contents('php://input'), true);
  $type = $payload['type'] ?? null;
}

switch ($type) {
  case 'membership':
    requirePost();
    handleMembership($payload);
    break;

  case 'posts':
    handlePosts();

  case 'news_updates':
    handleNewsUpdates();

  default:
    sendResponse('Invalid action/type');
}

function handlePosts($db = new Database)
{
  $conn = $db->getConnection();
  $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
  $limit = 6;
  $offset = ($currentPage - 1) * $limit;

  try {
    $totalPosts = $db->fetchOne(
      'SELECT COUNT(*) as total FROM posts WHERE status = "Published"
        AND category != "Announcement"'
    );
    $totalRows = $totalPosts['total'] ?? 0;
    $totalPages = ceil($totalRows / $limit);

    $sql = 'SELECT post_id, title, category, image_path, content, created_at
            FROM posts WHERE status = "Published" AND category != "Announcement"
            ORDER BY created_at DESC LIMIT ? OFFSET ?';

    $stmt = $conn->prepare($sql);

    $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);

    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($posts as &$post) {
      $createdAt = new DateTime($post['created_at']);
      $post['created_at'] = $createdAt->format('M. j, Y');
    }
    unset($post);

    sendResponse('Posts successfully fetched', [
      'activities' => $posts,
      'pagination' => [
        'current_page' => $currentPage,
        'total_pages' => (int)$totalPages,
        'total_records' => (int)$totalRows
      ]
    ], 'success');
  } catch (\Throwable $e) {
    error_log("Error fetching posts $e");
  }
}

function handleNewsUpdates($db = new Database)
{
  $conn = $db->getConnection();
  $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
  $limit = 4;
  $offset = ($currentPage - 1) * $limit;

  try {
    $totalPosts = $db->fetchOne(
      'SELECT COUNT(*) as total FROM posts WHERE status = "Published"
        AND category = "Announcement"'
    );
    $totalRows = $totalPosts['total'] ?? 0;
    $totalPages = ceil($totalRows / $limit);

    $sql = 'SELECT post_id, title, category, image_path, content, created_at
            FROM posts WHERE status = "Published" AND category = "Announcement"
            ORDER BY created_at DESC LIMIT ? OFFSET ?';

    $stmt = $conn->prepare($sql);

    $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);

    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($posts as &$post) {
      $createdAt = new DateTime($post['created_at']);
      $post['created_at'] = $createdAt->format('M. j, Y');
    }
    unset($post);

    sendResponse('Posts successfully fetched', [
      'news_updates' => $posts,
      'pagination' => [
        'current_page' => $currentPage,
        'total_pages' => (int)$totalPages,
        'total_records' => (int)$totalRows
      ]
    ], 'success');
  } catch (\Throwable $e) {
    error_log("Error fetching posts $e");
  }
}

function handleMembership($payload, $db = new Database)
{
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
    'date_joined',
    'terms_privacy_consent',
    'liability_waiver'
  ];

  $optionalFields = ['middle_name', 'sponsor', 'other_club_affiliation'];
  $checkboxFields = ['terms_privacy_consent', 'liability_waiver'];

  foreach ($requiredFields as $field) {
    if (!isset($payload[$field]) || trim((string)$payload[$field]) === '') {
      sendResponse('Please fill out all required fields correctly', [], 'warning');
    }
  }

  foreach ($checkboxFields as $field) {
    $value = $payload[$field] ?? null;
    if ($value !== '1') {
      sendResponse('You must agree to the terms, privacy consent, and liablity waiver', [], 'warning');
    }
  }

  $firstName = $payload['first_name'];
  $lastName = $payload['last_name'];
  $middleName = $payload['middle_name'];
  $phoneNumber = $payload['phone_number'];
  $email = $payload['email'];

  $sql = 'SELECT 1 FROM people
          WHERE first_name = ? AND last_name = ? AND middle_name = ?
          LIMIT 1';
  $check = $db->fetchOne($sql, [$firstName, $lastName, $middleName]);

  if ($check) {
    sendResponse('This person is already registered', [], 'warning');
  }

  $sql = "SELECT 1 FROM people WHERE phone_number = ? LIMIT 1";
  $check = $db->fetchOne($sql, [$phoneNumber]);

  if ($check) {
    sendResponse('Phone number is already in used', [], 'warning');
  }

  $sql = "SELECT 1 FROM people WHERE email = ? LIMIT 1";
  $check = $db->fetchOne($sql, [$email]);

  if ($check) {
    sendResponse('Email is already in used', [], 'warning');
  }

  $params = [];

  foreach (array_merge($requiredFields, $optionalFields) as $field) {
    $value = $payload[$field] ?? '';
    $params[":$field"] = is_string($value) ? trim($value) : (string)$value;
  }

  foreach ($checkboxFields as $field) {
    unset($params[":$field"]);
  }

  foreach ($optionalFields as $field) {
    if (empty($payload[$field])) {
      $params[":$field"] = '';
    }
  }

  try {
    $db->beginTransaction();

    $sql = 'INSERT INTO people (
              first_name, last_name, middle_name, date_of_birth, civil_status,
              blood_type, home_address, phone_number, email,
              emergency_contact_name, emergency_contact_number, occupation,
              license_number, motorcycle_brand, motorcycle_model, sponsor,
              other_club_affiliation, chapter_id, date_joined
            ) VALUES (
              :first_name, :last_name, :middle_name, :date_of_birth,
              :civil_status, :blood_type, :home_address, :phone_number, :email,
              :emergency_contact_name, :emergency_contact_number, :occupation,
              :license_number, :motorcycle_brand, :motorcycle_model, :sponsor,
              :other_club_affiliation, :chapter_id, :date_joined
            )';
    $db->execute($sql, $params);

    $personId = $db->lastInsertId();
    $db->execute('INSERT INTO aspirants (person_id) VALUES (?)', [$personId]);

    $usernameBase = strtolower(substr($params[':first_name'], 0, 1) . $params[':last_name']);
    $username = preg_replace('/[^a-z0-9]/', '', $usernameBase) . rand(100, 999);

    $temporaryPassword = bin2hex(random_bytes(4));
    $hashedPassword = password_hash($temporaryPassword, PASSWORD_DEFAULT);

    $sql = 'INSERT INTO users (person_id, username, password, role_id)
            VALUES (?, ?, ?, 5)';

    $db->execute($sql, [$personId, $username, $hashedPassword]);

    $db->commit();

    $recipientEmail = $params[':email'];
    $recipientName = $params[':first_name'] . ' ' . $params[':last_name'];
    $tempUsername = $username;
    $tempPassword = $temporaryPassword;

    include __DIR__ . '/../api/send_email.php';

    sendResponse('Application submitted. Please check your email', [], 'success');
  } catch (\Throwable $e) {
    if ($db->inTransaction()) {
      $db->rollBack();
    }

    if ($e->getCode() === '23000') {
      sendResponse('An input is already in used', [], 'warning');
    }

    error_log("Error processing membership application: $e");
    sendResponse('An error has occured. Please try again later');
  }
}

# helper functions

function requirePost()
{
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    sendResponse('Invalid request method');
  }
}

function sendResponse($message, $data = [], $status = 'error')
{
  $response = [
    'message' => $message,
    'data' => $data,
    'status' => $status
  ];

  echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  exit;
}
