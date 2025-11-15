let lastMessageID = 0;

const $messageField = $('#message-field');
const $sendBtn = $('#send-button');

$messageField.focus();

function getData(payload, type) {
  const data = {
    payload,
    action: type,
  };

  $.ajax({
    url: '../../api/messages.php',
    method: 'POST',
    data: JSON.stringify(data),
    contentType: 'application/json',
    dataType: 'json',
    success: (res) => {
      if (res.status === 'success' && res.data && Array.isArray(res.data)) {
        renderMessages(res.data);
      } else {
        console.log(res.message);
      }
    },
    error: function (xhr, status, error) {
      console.error('Thrown error:', error);
    },
  });
}

function loadMessages() {
  if (!CONTACT_ID) return;

  getData(
    {
      userID: CONTACT_ID,
      lastMessageID: lastMessageID,
    },
    'getMessages'
  );
}

function renderMessages(messages) {
  const $chatBody = $('.conversation-body');

  if (messages.length === 0) return;

  let shouldScroll = false;

  messages.forEach((msg) => {
    if (msg.message_id > lastMessageID) {
      shouldScroll = true;
    }

    const isMine = msg.sender_id === CURRENT_USER_ID;
    const message = (msg.message || '').trim();
    const dateTime = formatTime(msg.created_at);

    const messageHTML = `
      <div class="${isMine ? 'right-bubble' : 'left-bubble'} p-2">
        ${
          !isMine
            ? `<img class="chat-avatar rounded-circle mr-2" src="https://i.pravatar.cc/?img=12">`
            : ''
        }

        <div class="message-container rounded p-2">${message}</div>
        <small class="time">${dateTime}</small>
      </div>
    `;
    $chatBody.append(messageHTML);

    lastMessageID = msg.message_id;
  });

  if (
    shouldScroll &&
    messages.some((msg) => msg.sender_id === CURRENT_USER_ID)
  ) {
    $chatBody.scrollTop($chatBody[0].scrollHeight);
  }
}

function sendMessage() {
  const message = $messageField.val().trim();

  if (!message) return;

  if (!CONTACT_ID) {
    alert('Please select a user first');
  }

  getData(
    {
      message: message,
      userID: CONTACT_ID,
    },
    'sendMessage'
  );

  $messageField.val('');

  loadMessages();
}

$sendBtn.click(sendMessage);

// send message when Enter key is pressed
$messageField.on('keydown', function (event) {
  if (event.key === 'Enter' && !event.shiftKey) {
    event.preventDefault();
    sendMessage();
  }
});

$(document).ready(() => {
  loadMessages();
  setInterval(loadMessages, 3000);
});

function formatTime(timeStr) {
  if (!timeStr) return '';

  const now = new Date();
  const msgDate = new Date(timeStr);

  const isToday = msgDate.toDateString() === now.toDateString();

  const yesterDay = new Date();
  yesterDay.setDate(now.getDate() - 1);
  const isYesterday = msgDate.toDateString() === yesterDay.toDateString();

  const sameYear = msgDate.getFullYear() === now.getFullYear();

  if (isToday) {
    return msgDate.toLocaleTimeString('en-US', {
      hour: '2-digit',
      minute: '2-digit'
    });
  } else if (isYesterday) {
    return 'Yesterday';
  } else if (sameYear) {
    return msgDate.toLocaleDateString('en-US', {
      month: 'short',
      day: 'numeric'
    });
  } else {
    return msgDate.toLocaleDateString('en-US', {
      month: 'short',
      day: 'numeric',
      year: 'numeric'
    });
  }
}

function switchChat(userID) {
  CONTACT_ID = userID;
  lastMessageID = 0;
  $('.conversation-body').empty();
  loadMessages();
}
