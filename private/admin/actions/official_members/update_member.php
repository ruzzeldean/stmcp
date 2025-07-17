<?php
header('Content-Type: application/json');

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/../../../config/connection.php';

function sendResponse($status, $message) {
  echo json_encode([
    'status' => $status,
    'message' => $message
  ]);
  exit;
}

function sanitizeString($input) {
  $input = trim((string) $input);
  $input = strip_tags($input);
  $input = preg_replace('/[\x00-\x1F\x7F]/u', '', $input);
  return $input;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  sendResponse('error', 'Invalid request method');
}

if (!isset($_POST['csrfToken']) || $_POST['csrfToken'] !== $_SESSION['csrfToken']) {
  sendResponse('error', 'Invalid token');
}

$requiredFields = [
  'memberID',
  'firstName',
  'lastName',
  'dateOfBirth',
  'bloodType',
  'address',
  'phoneNumber',
  'contactPersonNumber',
  'email',
  'occupation',
  'driversLicenseNumber',
  'brand',
  'model',
  'engineSizeCC',
  'sponsoredBy',
  'affiliations',
  'chapter'
];

foreach ($requiredFields as $field) {
  if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
    sendResponse('error', "Missing or empty field: $field");
  }
}

$memberID = filter_input(INPUT_POST, 'memberID', FILTER_VALIDATE_INT);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$dateOfBirth = trim((string) $_POST['dateOfBirth']);
$phoneNumber = trim((string) $_POST['phoneNumber']);
$contactPersonNumber = trim((string) $_POST['contactPersonNumber']);

if (!$memberID) {
  sendResponse('error', 'Invalid member ID');
}

if (!$email) {
  sendResponse('error', 'Invalid email address');
}

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateOfBirth)) {
  sendResponse('error', 'Date of Birth must be in YYYY-MM-DD format');
}

$firstName = sanitizeString($_POST['firstName']);
$middleName = sanitizeString($_POST['middleName']);
$lastName = sanitizeString($_POST['lastName']);
$bloodType = sanitizeString($_POST['bloodType']);
$address = sanitizeString($_POST['address']);
$occupation = sanitizeString($_POST['occupation']);
$driversLicenseNumber = sanitizeString($_POST['driversLicenseNumber']);
$brand = sanitizeString($_POST['brand']);
$model = sanitizeString($_POST['model']);
$engineSizeCC = sanitizeString($_POST['engineSizeCC']);
$sponsoredBy = sanitizeString($_POST['sponsoredBy']);
$affiliations = sanitizeString($_POST['affiliations']);
$chapter = sanitizeString($_POST['chapter']);

try {
  $sql = 'UPDATE official_members SET
    first_name = :firstName,
    middle_name = :middleName,
    last_name = :lastName,
    date_of_birth = :dateOfBirth,
    blood_type = :bloodType,
    address = :address,
    phone_number = :phoneNumber,
    contact_person_number = :contactPersonNumber,
    email = :email,
    occupation = :occupation,
    drivers_license_number = :driversLicenseNumber,
    brand = :brand,
    model = :model,
    engine_size_cc = :engineSizeCC,
    sponsored_by = :sponsoredBy,
    affiliations = :affiliations,
    team_chapter = :chapter
    WHERE member_id = :memberID';

  $update = $conn->prepare($sql);
  $update->execute([
    'memberID' => $memberID,
    'firstName' => $firstName,
    'middleName' => $middleName,
    'lastName' => $lastName,
    'dateOfBirth' => $dateOfBirth,
    'bloodType' => $bloodType,
    'address' => $address,
    'phoneNumber' => $phoneNumber,
    'contactPersonNumber' => $contactPersonNumber,
    'email' => $email,
    'occupation' => $occupation,
    'driversLicenseNumber' => $driversLicenseNumber,
    'brand' => $brand,
    'model' => $model,
    'engineSizeCC' => $engineSizeCC,
    'sponsoredBy' => $sponsoredBy,
    'affiliations' => $affiliations,
    'chapter' => $chapter
  ]);

  if ($update->rowCount() > 0) {
    sendResponse('success', 'Member updated successfully');
  } else {
    $check = $conn->prepare('SELECT member_id FROM official_members WHERE member_id = :memberID');
    $check->execute(['memberID' => $memberID]);
    
    if ($check->fetch(PDO::FETCH_ASSOC)) {
      sendResponse('success', 'No changes were made. Data is already up to date');
    } else {
      sendResponse('error', 'No member found with the ID provided');
    }
  }
} catch (Throwable $ex) {
  error_log('Error: ' . $ex->getMessage());
  sendResponse('error', 'An error occured while updating the member. Please try again later');
}
