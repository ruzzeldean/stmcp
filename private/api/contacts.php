<?php
require_once __DIR__ . '/../includes/helpers.php';

requireLogin();

$db = new Database();

$currentUser = $_SESSION['userID'];

$sql = 'SELECT
          u.user_id,
          CONCAT(u.first_name, " ", u.last_name) AS full_name,
          u.username,
          m.sender_id,
          m.message AS last_message,
          m.created_at AS last_message_time
        FROM users u
        INNER JOIN (
          SELECT
            IF(sender_id = ?, receiver_id, sender_id) AS contact_id,
            MAX(message_id) AS last_message_id
          FROM messages
          WHERE sender_id = ? OR receiver_id = ?
          GROUP BY contact_id
        ) recent ON u.user_id = recent.contact_id
        INNER JOIN messages m ON m.message_id = recent.last_message_id
        ORDER BY m.created_at DESC';

$contacts = $db->fetchAll($sql, [$currentUser, $currentUser, $currentUser]);

sendResponse('success', 'Contacts successfully fetched', $contacts);
