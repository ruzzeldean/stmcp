<?php
include __DIR__ . '/../../includes/helpers.php';

requireLogin();

try {
  $db = new Database();
  $currentUser = $_SESSION['user_id'];
  $search = isset($_GET['search']) ? trim($_GET['search']) : '';

  $sql = 'SELECT
          u.user_id,
          u.username,
          CONCAT(p.first_name, " ", p.last_name) AS full_name,
          msg.sender_id,
          msg.message AS last_message,
          msg.created_at AS last_message_time
        FROM users AS u
        INNER JOIN people AS p
          ON u.person_id = p.person_id
        INNER JOIN (
          SELECT
            IF(sender_id = ?, receiver_id, sender_id) AS contact_id,
            MAX(message_id) AS last_message_id
          FROM messages
          WHERE sender_id = ? OR receiver_id = ?
          GROUP BY contact_id
        ) recent ON u.user_id = recent.contact_id
        INNER JOIN messages msg ON msg.message_id = recent.last_message_id';

  $params = [$currentUser, $currentUser, $currentUser];

  if ($search !== '') {
    $sql .= ' WHERE CONCAT(p.first_name, " ", p.last_name) LIKE ?';
    $params[] = "%$search%";
  }

  $sql .= ' ORDER BY msg.created_at DESC';

  $contacts = $db->fetchAll($sql, $params);

  sendResponse('Contacts successfully fetched', $contacts);
} catch (Throwable $e) {
  error_log("Error fetching contacts: $e");
}
