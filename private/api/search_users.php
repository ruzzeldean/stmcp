<?php
require_once __DIR__ . '/../includes/helpers.php';

requireLogin();

$db = new Database();
$currentUser = $_SESSION['userID'];
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search === '') {
  sendResponse('success', 'No search term provided', []);
}

$sql = 'SELECT
          u.user_id,
          CONCAT(u.first_name, " ", u.last_name) AS full_name,
          u.username
        FROM users u
        WHERE
          (u.first_name LIKE ? OR u.last_name LIKE ? OR CONCAT(u.first_name, " ", u.last_name) LIKE ?)
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

sendResponse('success', 'Search results fetched', $results);
