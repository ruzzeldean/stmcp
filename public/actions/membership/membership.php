<?php
require_once __DIR__ . '/../../includes/helpers.php';

// rate limiting
// input length limits
// unique fields validation (duplicate entry)

requirePost();

function validateDate($date)
{
  $d = DateTime::createFromFormat('Y-m-d', $date);
  return $d && $d->format('Y-m-d') === $date;
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

/* if (!preg_match('/^(?:\+63-?|0)\d{3}-\d{3}-\d{4}$/', $data['phone_number'])) {
  sendResponse('error', 'Invalid phone number');
} */

$data['phone_number'] = preg_replace('/[^0-9+]/', '', $data['phone_number']);

/* if (!preg_match('/^(?:\+63-?|0)\d{3}-\d{3}-\d{4}$/', $data['emergency_contact_number'])) {
  sendResponse('error', 'Invalid emergency contact number');
} */

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

  $stmt = $db->execute($sql, $data);

  if ($stmt) {
    $aspirantId = $db->lastInsertId();

    $username = strtolower(substr($data['first_name'], 0, 1) . $data['last_name']);
    $username = preg_replace('/[^a-z0-9]/', '', $username) . rand(100, 999);

    $password = bin2hex(random_bytes(4));
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = 'INSERT INTO users (member_id, username, password, role_id)
            VALUES (:member_id, :username, :password, :role_id)';

    $db->execute($sql, [
      'member_id' => $aspirantId,
      'username' => $username,
      'password' => $hashedPassword,
      'role_id' => 5
    ]);

    $recipientEmail = $data['email'];
    $recipientName = $data['first_name'] . ' ' . $data['last_name'];
    $tempUsername = $username;
    $tempPassword = $password;

    require __DIR__ . '/send_email.php';

    sendResponse('success', 'Application submitted');
  }
} catch (Throwable $e) {
  error_log('Error member registration: ' . $e/* ->getMessage() */);
  sendResponse('error', 'Something went wrong. Please try again later');
}
