<?php
include __DIR__ . '/../../includes/helpers.php';

requireLogin();
/* requireCsrf(); */

const MAX_FILE_SIZE = 5 * 1024 * 1024;
const ALLOWED_MIME_TYPES = ['image/jpg', 'image/jpeg', 'image/png'];
const UPLOAD_DIRECTORY = '../../storage/uploads/donations/';
const ALLOWED_EXTENSIONS = [
  'image/jpeg' => '.jpg',
  'image/jpg' => '.jpg',
  'image/png' => '.png'
];

$type = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $payload = json_decode(file_get_contents('php://input'), true);
  $type = $payload['type'] ?? null;
}

$type = $_GET['type'] ?? null;
$status = $_GET['status'] ?? null;

switch ($type) {
  case 'fetch_donations':
    fetchDonations();
    break;

  case 'create':
    requireAdminModerator();
    createDonation($payload);
    break;

  case 'handle_pending':
    requireAdmin();
    handlePendingDonation($payload);
    break;

  case 'active_donations':
  case 'closed_donations':
  case 'pending_donations':
    donationsList();
    break;

  case 'donate':
    requireCsrf();
    donate();
    break;
  /*case 'update':
    updateDonation($payload);
    break; */

  case 'fetch_donors':
    fetchDonors();
    break;

  default:
    http_response_code(400);
    sendResponse('Invalid request type', [], 'error');
}

function fetchDonations($db = new Database)
{
  $userId = $_SESSION['user_id'] ?? null;
  $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
  $limit = 5;
  $offset = ($currentPage - 1) * $limit;

  try {
    $totalActive = $db->fetchOne(
      'SELECT COUNT(*) AS total FROM donations WHERE status = "Active"'
    );

    $totalClosed = $db->fetchOne(
      'SELECT COUNT(*) AS total FROM donations WHERE status = "Closed"'
    );

    $totalPending = ['total' => 0];
    if (in_array($_SESSION['role_id'], [1, 2])) {
      $totalPending = $db->fetchOne(
        'SELECT COUNT(*) AS total FROM donations WHERE status = "Pending"'
      );
    }

    $countSql = 'SELECT COUNT(*) AS total FROM donors WHERE user_id = ?';
    $totalMyDonations = $db->fetchOne($countSql, [$userId]);
    $totalRows = $totalMyDonations['total'] ?? 0;
    $totalPages = ceil($totalRows / $limit);

    $sql = "SELECT
              dt.donation_id,
              dt.purpose,
              dt.start_date,
              dt.due_date,
              dt.status,
              dr.amount
            FROM donations AS dt
            INNER JOIN donors AS dr ON dt.donation_id = dr.donation_id
            WHERE dr.user_id = ?
            ORDER BY dr.created_at DESC
            LIMIT $limit OFFSET $offset";
    $myDonations = $db->fetchAll($sql, [$userId]);

    sendResponse('Donations data fetched', [
      'stats' => [
        'active' => $totalActive['total'] ?? 0,
        'closed' => $totalClosed['total'] ?? 0,
        'pending' => $totalPending['total'] ?? 0,
      ],
      'pagination' => [
        'current_page' => $currentPage,
        'total_pages' => (int)$totalPages,
        'total_records' => (int)$totalRows
      ],
      'my_donations' => $myDonations
    ]);
  } catch (\Throwable $e) {
    error_log("Error fetching records $e");
  }
}

function createDonation($payload, $db = new Database)
{
  $createdBy = $_SESSION['user_id'];
  $roleId = $_SESSION['role_id'];
  $purpose = isset($payload['purpose']) ? trim($payload['purpose']) : null;
  $description = isset($payload['description']) ? trim($payload['description']) : null;
  $startDate = $payload['start_date'] ?? null;
  $dueDate = $payload['due_date'] ?? null;

  if (empty($purpose)) {
    sendResponse('Purpose is required', [], 'warning');
  }

  if (empty($description)) {
    sendResponse('Description is required', [], 'warning');
  }

  if ($startDate !== null && !validateDate($startDate)) {
    sendResponse('Invalid or missing start date', [], 'warning');
  }

  if ($dueDate !== null && !validateDate($dueDate)) {
    sendResponse('Invalid or missing start date', [], 'warning');
  }

  $status = $roleId === 1 || $roleId === 2 ? 'Active' : 'Pending';

  $params = [
    $purpose,
    $description,
    $startDate,
    $dueDate,
    $createdBy,
    $status
  ];

  try {
    $sql = 'INSERT INTO donations
              (purpose, description, start_date, due_date, created_by, status)
            VALUES (?, ?, ?, ?, ?, ?)';
    $stmt = $db->execute($sql, $params);

    if ($stmt) {
      sendResponse('Donation created successfully');
    }
  } catch (PDOException $e) {
    error_log("Server Error $e");
    sendResponse('Server error');
  } catch (\Throwable $e) {
    error_log("Error creating donation: $e");
    sendResponse('Create donation failed. Please try again later');
  }
}

function handlePendingDonation($payload, $db = new Database)
{
  $action = isset($payload['action']) ? trim($payload['action']) : null;
  $donationId = isset($payload['donation_id']) ? trim($payload['donation_id']) : null;

  if (empty($action)) {
    sendResponse('Invalid action', [], 'error');
  }

  if (empty($donationId)) {
    sendResponse('Missing donation ID', [], 'error');
  }

  $status = $action === 'approve' ? 'Active' : 'Rejected';

  try {
    $sql = 'UPDATE donations
            SET status = :status
            WHERE donation_id = :donation_id';
    $db->execute($sql, [$status, $donationId]);

    if ($action === 'approve') {
      sendResponse('Donation successfully approved');
    } else {
      sendResponse('Donation successfully rejected');
    }
  } catch (\Throwable $e) {
    error_log("Error approving/rejecting donation: $e");
    sendResponse('Action failed. Please try again later', [], 'erorr');
  }
}

function donationsList($db = new Database)
{
  if (!in_array($_GET['status'], ['active', 'closed', 'pending', 'rejected'])) {
    sendResponse('Invalid status', [], 'error');
  }

  $status = $_GET['status'] ?? null;

  $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
  $limit = 5;
  $offset = ($currentPage - 1) * $limit;

  try {
    $countSql = 'SELECT COUNT(*) AS total
                  FROM donations AS d
                  WHERE d.status = :status';
    $totalRows = $db->fetchOne($countSql, ['status' => $status])['total'];
    $totalPages = ceil($totalRows / $limit);

    $sql = "SELECT
              d.donation_id,
              d.purpose,
              d.start_date,
              d.due_date,
              d.status,
              CONCAT(creator.first_name, ' ', creator.last_name) AS creator,
              CONCAT(approver.first_name, ' ', approver.last_name) AS approver
            FROM donations AS d
            INNER JOIN users AS creator_user
              ON d.created_by = creator_user.user_id
            INNER JOIN people AS creator
              ON creator_user.person_id = creator.person_id
            LEFT JOIN users AS approver_user
              ON d.approved_by = approver_user.user_id
            LEFT JOIN people AS approver
              ON approver_user.person_id = approver.person_id
            WHERE d.status = ?
            ORDER BY d.due_date ASC
            LIMIT $limit OFFSET $offset";
    $donations = $db->fetchAll($sql, [$status]);

    foreach ($donations as &$donation) {
      $start = new DateTime($donation['start_date']);
      $due = new DateTime($donation['due_date']);

      $donation['start_date'] = $start->format('M j, Y');
      $donation['due_date_formatted'] = $due->format('M j, Y');

      $donation['approver'] = $donation['approver'] ?? '';
    }
    unset($donation);

    sendResponse('Donations data successfully fetched', [
      'pagination' => [
        'current_page' => $currentPage,
        'total_pages' => (int)$totalPages,
        'total_records' => (int)$totalRows
      ],
      'donations' => $donations
    ]);
  } catch (\Throwable $e) {
    error_log("Error fetching records $e");
  }
}

function donate($db = new Database)
{
  $userId = $_SESSION['user_id'];
  $donationId = trim($_POST['donation_id'] ?? '');
  $inputAmount = $_POST['amount'] ?? null;

  if ($donationId === '') {
    sendResponse('Missing donation ID', [], 'error');
  }

  if ($inputAmount === null || trim($inputAmount) === '') {
    sendResponse('Amount is required', [], 'error');
  } else {
    $amount = filter_var($inputAmount, FILTER_VALIDATE_FLOAT);

    if ($amount === false) {
      sendResponse('Please enter a numeric amount', [], 'error');
    } elseif ($amount <= 0) {
      sendResponse('Amount must be greater than zero', [], 'error');
    }
  }

  $uploadedFileKey = 'image';

  if (
    !isset($_FILES[$uploadedFileKey]) ||
    $_FILES[$uploadedFileKey]['error'] === UPLOAD_ERR_NO_FILE
  ) {
    sendResponse('Image is required. Please select a file', [], 'error');
  }

  $file = $_FILES[$uploadedFileKey];

  if ($file['error'] !== UPLOAD_ERR_OK) {
    sendResponse('Image upload failed, Please try again', [], 'error');
  }

  if ($file['size'] === 0) {
    sendResponse('Uploaded file is empty', [], 'error');
  }

  if ($file['size'] > MAX_FILE_SIZE) {
    sendResponse('File too large. Maximum allowed is 5 MB', [], 'error');
  }

  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $detectedMimeType = $finfo ? finfo_file($finfo, $file['tmp_name']) : false;

  if (
    $detectedMimeType === false ||
    !in_array($detectedMimeType, ALLOWED_MIME_TYPES, true)
  ) {
    sendResponse('Invalid file type. Only JPEG/JPG and PNG are allowed', [], 'error');
  }

  $imageInfo = getimagesize($file['tmp_name']);
  if ($imageInfo === false) {
    sendResponse('File is not a valid image', [], 'error');
  }

  $fileExtension = ALLOWED_EXTENSIONS[$detectedMimeType] ?? null;
  if ($fileExtension === null) {
    sendResponse('Unsupported file format', [], 'error');
  }

  $fileExtension = strtolower($fileExtension);
  $fileName = 'receipt_' . bin2hex(random_bytes(16)) . $fileExtension;
  $destinationPath = UPLOAD_DIRECTORY . $fileName;

  if (!is_dir(UPLOAD_DIRECTORY)) {
    mkdir(UPLOAD_DIRECTORY, 0755, true);
  }

  if (!move_uploaded_file($file['tmp_name'], $destinationPath)) {
    sendResponse('Failed to save image. Please try again later', [], 'error');
  }

  try {
    $sql = 'SELECT 1 FROM donations
            WHERE donation_id = ? AND status = "Active"
            LIMIT 1';
    $donation = $db->fetchOne($sql, [$donationId]);

    if (!$donation) {
      if (isset($destinationPath) && file_exists($destinationPath)) {
        unlink($destinationPath);
      }
      sendResponse('Donation not found or already closed', [], 'error');
    }

    $sql = 'INSERT INTO donors (donation_id, user_id, proof_image, amount)
            VALUES (?, ?, ?, ?)';
    $params = [$donationId, $userId, $fileName, $amount];

    $db->execute($sql, $params);

    sendResponse('Thank you! Your donation has been record');
  } catch (\Throwable $e) {
    if ($e->getCode() === '23000') {
      if (isset($destinationPath) && file_exists($destinationPath)) {
        unlink($destinationPath);
      }
    }
    sendResponse('You have already donated to this campaign', [], 'info');
  } catch (\Throwable $e) {
    if (isset($destinationPath) && file_exists($destinationPath)) {
      unlink($destinationPath);
    }
    error_log("Error processing donation: $e");
    sendResponse('Error processing donation. Please try again later', [], 'error');
  }
}

function fetchDonors($db = new Database)
{
  $donationId = $_GET['donation_id'] ?? '';

  if (empty($donationId)) {
    sendResponse('Missing donation ID', [], 'error');
  }

  $sql = 'SELECT
            d.amount,
            d.proof_image,
            CONCAT(p.first_name, " ", p.last_name) AS donor_name
          FROM donors AS d
          INNER JOIN users AS u
            ON d.user_id = u.user_id
          LEFT JOIN people AS p
            ON p.person_id = u.person_id
          WHERE d.donation_id = ?';
  $donors = $db->fetchAll($sql, [$donationId]);

  sendResponse('Sumakses', $donors);
}
