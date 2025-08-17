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

function sanitizeString($input)
{
  $input = trim((string) $input);
  $input = strip_tags($input);
  $input = preg_replace('/[\x00-\x1F\x7F]/u', '', $input);
  return $input;
}

function validateDate($date)
{
  $d = DateTime::createFromFormat('Y-m-d', $date);
  return $d && $d->format('Y-m-d') === $date;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  sendResponse('error', 'Invalid request method');
}

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrfToken']) {
  sendResponse('error', 'Invalid token');
}

$requiredFields = [
  'member_id',
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

$data = [];

foreach ($requiredFields as $field) {
  if (empty($_POST[$field] ?? '')) {
    sendResponse('error', "Missing or empty field: $field");
  }
  $data[$field] = trim($_POST[$field]);
}

$memberID = filter_input(INPUT_POST, 'member_id', FILTER_VALIDATE_INT);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$dateOfBirth = trim((string) $_POST['date_of_birth']);
$dateJoined = trim((string) $_POST['date_joined']);
$phone_number = trim((string) $_POST['phone_number']);
$emergency_contact_name = trim((string) $_POST['emergency_contact_name']);

if (!$memberID) {
  sendResponse('error', 'Invalid member ID');
}

if (!validateDate($data['date_of_birth'])) {
  sendResponse('error', 'Invalid date of birth');
}

if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
  sendResponse('error', 'Invalid email format');
}

if (!preg_match('/^(?:\+63-?|0)\d{3}-\d{3}-\d{4}$/', $data['phone_number'])) {
  sendResponse('error', 'Invalid phone number');
}

$data['phone_number'] = preg_replace('/[^0-9+]/', '', $data['phone_number']);

if (!preg_match('/^(?:\+63-?|0)\d{3}-\d{3}-\d{4}$/', $data['emergency_contact_number'])) {
  sendResponse('error', 'Invalid emergency contact number');
}

$data['emergency_contact_number'] = preg_replace('/[^0-9+]/', '', $data['emergency_contact_number']);

if (!validateDate($data['date_joined'])) {
  sendResponse('error', 'Invalid date joined');
}

$data['middle_name'] = trim($_POST['middle_name'] ?? '');
$data['sponsor'] = trim($_POST['sponsor'] ?? '');
$data['other_club_affiliation'] = trim($_POST['other_club_affiliation'] ?? '');

try {
  $sql = 'UPDATE official_members SET
    first_name = :first_name,
    last_name = :last_name,
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
    chapter_id = :chapter_id,
    date_joined = :date_joined,
    middle_name = :middle_name,
    sponsor = :sponsor,
    other_club_affiliation = :other_club_affiliation
    WHERE member_id = :member_id';

  $update = $conn->prepare($sql);
  $update->execute($data);

  if ($update->rowCount() > 0) {
    sendResponse('success', 'Member updated successfully');
  } else {
    $check = $conn->prepare('SELECT member_id FROM official_members WHERE member_id = :member_id');
    $check->execute(['member_id' => $memberID]);

    if ($check->fetch()) {
      sendResponse('success', 'No changes were made. Data is already up to date');
    } else {
      sendResponse('error', 'No member found with the ID provided');
    }
  }
} catch (Throwable $ex) {
  error_log('Failed updating member: ' . $ex->getMessage());
  sendResponse('error', 'An error occured while updating the member. Please try again later');
}
