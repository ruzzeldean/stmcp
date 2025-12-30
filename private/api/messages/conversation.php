<?php
require_once __DIR__ . '/../../includes/helpers.php';

requireLogin();

$rawInput = file_get_contents('php://input');
$requestData = json_decode($rawInput);

if (!$requestData) {
  sendResponse('Invalid JSON input', []);
}

$db = new Database();
$action = $requestData->action ?? null;

# TODO: validation for fields

switch ($action) {
  case 'sendMessage':
    $receiverID = $requestData->payload->userID ?? null;
    $message = trim($requestData->payload->message ?? '');
    $imageRaw = $requestData->payload->image ?? null; // Base64 string
    $imageName = null;

    // Process Image if present
    if ($imageRaw) {
      // Remove the data:image/png;base64, prefix
      $imageParts = explode(";base64,", $imageRaw);
      $imageTypeAux = explode("image/", $imageParts[0]);
      $imageType = $imageTypeAux[1];
      $imageBase64 = base64_decode($imageParts[1]);

      // Generate a unique filename
      $imageName = 'msg_' . uniqid() . '.' . $imageType;
      // Update the path to correctly point to your storage folder
      $filePath = __DIR__ . '/../../storage/uploads/messages/' . $imageName;

      // Save file to the 'uploads' directory
      file_put_contents($filePath, $imageBase64);
    }

    if (!$receiverID) {
      sendResponse('Missing required fields', [], 'error');
    }

    $sql = 'SELECT u.user_id FROM users AS u WHERE user_id = :user_id LIMIT 1';
    $recipient = $db->fetchOne($sql, ['user_id' => $receiverID]);

    if (!$recipient) {
      sendResponse('User not found');
    }

    $senderID = $_SESSION['user_id'];
    $conversationID = getConversationID($senderID, $receiverID);

    $messageData = [
      'conversation_id' => $conversationID,
      'sender_id' => $senderID,
      'receiver_id' => $receiverID,
      'message' => $message,
      /* 'image' => $image */
      'image' => $imageName // Store the filename/path instead of raw base64
    ];

    # insert msg to db
    $insertSql = 'INSERT INTO messages (conversation_id, sender_id, receiver_id, message, image) VALUES (:conversation_id, :sender_id, :receiver_id, :message, :image)';

    if (!$db->execute($insertSql, $messageData)) {
      sendResponse('Failed to send message');
    }

    sendResponse('Message sent successfully', [], 'success');
    break;

  case 'getMessages':
    $receiverID = $requestData->payload->userID ?? null;
    $lastMessageID = (int) ($requestData->payload->lastMessageID ?? 0);

    if (!$receiverID) {
      sendResponse('Missing user ID');
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

    sendResponse('Messages fetched', $messages, 'success');
    break;

  default:
    sendResponse('Unknown action');
}
