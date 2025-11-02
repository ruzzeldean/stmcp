<?php
require_once __DIR__ . '/../includes/helpers.php';

requireLogin();

$rawInput = file_get_contents('php://input');
$requestData = json_decode($rawInput);

if (!$requestData) {
  sendResponse('error', 'Invalid JSON input');
}

$db = new Database();
$action = $requestData->action ?? null;

# TODO: validation for fields

switch ($action) {
  case 'sendMessage':
    $receiverID = $requestData->payload->userID ?? null;
    $message = trim($requestData->payload->message ?? '');
    $image = $requestData->payload->image ?? null;

    if (!$receiverID || !$message) {
      sendResponse('error', 'Missing requiredfields');
    }

    $sql = 'SELECT u.user_id FROM users u WHERE user_id = :user_id LIMIT 1';
    $recipient = $db->fetchOne($sql, ['user_id' => $receiverID]);

    if (!$recipient) {
      sendResponse('error', 'User not found');
    }

    $senderID = $_SESSION['user_id'];
    $conversationID = getConversationID($senderID, $receiverID);

    $messageData = [
      'conversation_id' => $conversationID,
      'sender_id' => $senderID,
      'receiver_id' => $receiverID,
      'message' => $message,
      'image' => $image
    ];

    # insert msg to db
    $insertSql = 'INSERT INTO messages (conversation_id, sender_id, receiver_id, message, image) VALUES (:conversation_id, :sender_id, :receiver_id, :message, :image)';

    if (!$db->execute($insertSql, $messageData)) {
      sendResponse('error', 'Failed to send message');
    }

    sendResponse('success', 'Message sent successfully');
    break;

  case 'getMessages':
    $receiverID = $requestData->payload->userID ?? null;
    $lastMessageID = (int) ($requestData->payload->lastMessageID ?? 0);

    if (!$receiverID) {
      sendResponse('error', 'Missing user ID');
    }

    $senderID = $_SESSION['user_id'];
    $conversationID = getConversationID($senderID, $receiverID);

    $fetchSql = 'SELECT * FROM messages
                WHERE conversation_id = :conversation_id
                AND message_id > :last_id
                ORDER BY message_id ASC';
                
    $messages = $db->fetchAll($fetchSql, [
      'conversation_id' => $conversationID,
      'last_id' => $lastMessageID
    ]);

    sendResponse('success', 'Messages fetched', $messages);
    break;

  default:
    sendResponse('error', 'Unknown action');
}
