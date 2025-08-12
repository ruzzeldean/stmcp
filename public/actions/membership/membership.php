<?php
header('Content-Type: application/json');

// rate limiting
// input length limits
// unique fields validation (duplicate entry)

function sendResponse($status, $message)
{
  echo json_encode(['status' => $status, 'message' => $message]);
  exit;
}

function validateDate($date)
{
  $d = DateTime::createFromFormat('Y-m-d', $date);
  return $d && $d->format('Y-m-d') === $date;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  sendResponse('error', 'Invalid request method');
}

require_once __DIR__ . '/../../../config/connection.php';

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

$data = [];

foreach ($requiredFields as $field) {
  if (empty($_POST[$field] ?? '')) {
    sendResponse('error', 'All fields are required');
  }
  $data[$field] = trim($_POST[$field]);
}

if (!isset($_POST['terms_privacy_consent']) || !isset($_POST['liability_waiver'])) {
  sendResponse('error', 'All fields are required');
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
  $sql = 'INSERT INTO aspirants (
    first_name, last_name, date_of_birth, civil_status, blood_type, home_address, phone_number, email, emergency_contact_name, emergency_contact_number, occupation, license_number, motorcycle_brand, motorcycle_model, chapter_id, date_joined, middle_name, sponsor, other_club_affiliation
  ) VALUES (
    :first_name, :last_name, :date_of_birth, :civil_status, :blood_type, :home_address, :phone_number, :email, :emergency_contact_name, :emergency_contact_number, :occupation, :license_number, :motorcycle_brand, :motorcycle_model, :chapter_id, :date_joined, :middle_name, :sponsor, :other_club_affiliation
  )';

  $stmt = $conn->prepare($sql);
  $stmt->execute($data);

  sendResponse('success', 'Application submitted');
} catch (\Throwable $ex) {
  error_log('Error member registration: ' . $ex/* ->getMessage() */);
  sendResponse('error', 'Something went wrong. Please try again later');
}
