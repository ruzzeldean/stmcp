<?php
include __DIR__ . '/../../includes/helpers.php';

requireLogin();

$db = new Database();
$currentUser = $_SESSION['user_id'];
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search === '') {
  sendResponse('No search term provided');
}

try {
  $sql = 'SELECT
            u.user_id,
            CONCAT(p.first_name, " ", p.last_name) AS full_name,
            u.username
          FROM users AS u
          INNER JOIN people AS p ON u.person_id = p.person_id
          WHERE
            (p.first_name LIKE ? OR p.last_name LIKE ? OR CONCAT(p.first_name, " ", p.last_name) LIKE ?)
            AND u.user_id != ?
            AND u.user_id NOT IN (
              SELECT IF (sender_id = ?, receiver_id, sender_id)
              FROM messages
              WHERE sender_id = ? OR receiver_id = ?
            )
          ORDER BY full_name ASC
          LIMIT 20';

  $likeTerm = "%$search%";
  $params = [$likeTerm, $likeTerm, $likeTerm, $currentUser, $currentUser, $currentUser, $currentUser];

  $results = $db->fetchAll($sql, $params);

  sendResponse('Search results fetched', $results, 'success');
} catch (\Throwable $e) {
  error_log('Error searching user: ' . $e->getMessage());
  sendResponse('Failed to search user');
}
