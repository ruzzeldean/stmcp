/**
 * Conversation Module
 * Manages real-time message fetching, rendering, image previews, and sending.
 */
document.addEventListener('DOMContentLoaded', () => {
  // --- Constants & State ---
  let lastMessageID = 0;
  const REFRESH_INTERVAL_MS = 3000;

  // --- DOM Elements ---
  const chatBody = document.querySelector('.conversation-body');
  const messageField = document.getElementById('message-field');
  const sendButton = document.getElementById('send-button');

  // Image Upload Elements
  const imageInput = document.getElementById('image-input');
  const uploadBtn = document.getElementById('upload-image');
  const previewWrapper = document.getElementById('image-preview-wrapper');
  const previewImage = document.getElementById('img-preview-before-sending');
  const removeImageBtn = document.getElementById('remove-image-btn');

  // Initial UI Setup
  if (messageField) messageField.focus();

  // --- Core Functions ---

  /**
   * Fetches new messages since the last known message ID
   */
  async function loadMessages() {
    // CONTACT_ID is defined globally in the PHP view
    if (typeof CONTACT_ID === 'undefined' || !CONTACT_ID) return;

    await performAction(
      {
        userID: CONTACT_ID,
        lastMessageID: lastMessageID,
      },
      'getMessages'
    );
  }

  /**
   * Sends data to the backend API
   * @param {Object} payload - Data to be sent
   * @param {string} type - Action type (sendMessage/getMessages)
   */
  async function performAction(payload, type) {
    try {
      const response = await fetch('../api/messages/conversation.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ payload, action: type }),
      });

      const result = await response.json();

      if (result.status === 'success' && Array.isArray(result.data)) {
        renderMessages(result.data);
      } else if (result.status === 'error') {
        console.error('API Error:', result.message);
      }
    } catch (error) {
      console.error('Network Error:', error);
    }
  }

  /**
   * Renders messages into the chat body
   * @param {Array} messages - Array of message objects
   */
  function renderMessages(messages) {
    if (messages.length === 0) return;

    let shouldScroll = false;

    messages.forEach((msg) => {
      // Check if this message is newer than what we have
      if (msg.message_id > lastMessageID) {
        shouldScroll = true;

        // CURRENT_USER_ID is defined globally in the PHP view
        const isMine = msg.sender_id === CURRENT_USER_ID;
        const messageText = (msg.message || '').trim();
        const messageExist = messageText === '' ? 'd-none' : '';
        const timeString = formatTime(msg.created_at);
        const imageHtml = msg.image
          ? `<img src="../storage/uploads/messages/${msg.image}" class="img-fluid rounded d-block">`
          : '';
        const imageExist = imageHtml === '' ? 'd-none' : 'mt-1';

        const messageHtml = `
        <div class="${isMine ? 'right-bubble' : 'left-bubble'} p-2">
          ${
            !isMine
              ? `<img class="chat-avatar rounded-circle me-2" src="https://i.pravatar.cc/?img=12" alt="Avatar">`
              : ''
          }
          <div class="message-container rounded p-2 ${messageExist}">${messageText}</div>
          <div class="image-container ${imageExist}">${imageHtml}</div>
          <small class="time text-muted">${timeString}</small>
        </div>
      `;

        chatBody.insertAdjacentHTML('beforeend', messageHtml);
        lastMessageID = msg.message_id;
      }
    });

    // Auto-scroll to bottom if new messages were added
    if (shouldScroll) {
      chatBody.scrollTop = chatBody.scrollHeight;
    }
  }

  /**
   * Handles the sending of messages (Text + Image)
   */
  async function sendMessage() {
    const text = messageField.value.trim();
    const file = imageInput.files[0];
    let imageData = null;

    // Validation: Require at least text or an image
    if (!text && !file) return;

    if (!CONTACT_ID) {
      alert('Please select a user first');
      return;
    }

    // Convert image to Base64 if present
    if (file) {
      imageData = await convertFileToBase64(file);
    }

    // Send to server
    await performAction(
      {
        message: text,
        userID: CONTACT_ID,
        image: imageData,
      },
      'sendMessage'
    );

    // Reset UI state immediately
    messageField.value = '';
    resetImageInput();

    // Refresh to show the sent message
    loadMessages();
  }

  // --- Image Helper Functions ---

  function convertFileToBase64(file) {
    return new Promise((resolve, reject) => {
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => resolve(reader.result);
      reader.onerror = (error) => reject(error);
    });
  }

  function resetImageInput() {
    imageInput.value = '';
    previewWrapper.classList.add('d-none');
    previewImage.src = '';
  }

  // --- Event Listeners ---

  // Handle Image Selection
  uploadBtn.addEventListener('click', () => imageInput.click());

  imageInput.addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (e) => {
        previewImage.src = e.target.result;
        previewWrapper.classList.remove('d-none');
      };
      reader.readAsDataURL(file);
    }
  });

  // Handle Image Removal
  removeImageBtn.addEventListener('click', resetImageInput);

  // Handle Send Actions
  sendButton.addEventListener('click', sendMessage);

  messageField.addEventListener('keydown', (event) => {
    if (event.key === 'Enter' && !event.shiftKey) {
      event.preventDefault();
      sendMessage();
    }
  });

  // --- Utilities ---

  function formatTime(timeStr) {
    if (!timeStr) return '';
    const now = new Date();
    const msgDate = new Date(timeStr);
    const isToday = msgDate.toDateString() === now.toDateString();

    if (isToday) {
      return msgDate.toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit',
      });
    }
    return msgDate.toLocaleDateString('en-US', {
      month: 'short',
      day: 'numeric',
    });
  }

  /**
   * Global switchChat function to be called from messages list
   */
  window.switchChat = (userID) => {
    window.CONTACT_ID = userID;
    lastMessageID = 0;
    chatBody.innerHTML = '';
    resetImageInput();
    loadMessages();
  };

  // --- Initialization ---
  loadMessages();
  setInterval(loadMessages, REFRESH_INTERVAL_MS);
});
