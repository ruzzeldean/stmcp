/**
 * Messages Module
 * Handles fetching, searching, and rendering of message contacts.
 */
document.addEventListener('DOMContentLoaded', () => {
  // --- Constants & State ---
  const REFRESH_INTERVAL_MS = 5000;
  const DEBOUNCE_DELAY_MS = 300;

  let searchTimeout = null;
  let modalSearchTimeout = null;

  // --- DOM Elements ---
  const contactsContainer = document.getElementById('contacts-body');
  const mainSearchInput = document.getElementById('search-contacts');
  const addContactModal = document.getElementById('add-contact-modal');
  const modalSearchInput = document.getElementById('search-new-contacts');
  const modalResultsContainer = document.getElementById('new-contact-results');

  // --- Initialization ---
  fetchContacts();

  // --- Core Functions ---

  /**
   * Fetches the list of contacts/conversations from the server
   * @param {string} query - Optional search filter
   */
  async function fetchContacts(query = '') {
    try {
      const url = `../api/messages/messages.php?search=${encodeURIComponent(
        query
      )}`;
      const response = await fetch(url);

      if (!response.ok) throw new Error('Network response was not ok');

      const result = await response.json();

      if (result.status === 'success') {
        renderContacts(result.data, query);
      } else {
        console.error('Server error:', result.message);
      }
    } catch (error) {
      console.error('Error fetching contacts:', error);
    }
  }

  /**
   * Renders the contact list into the main UI
   */
  function renderContacts(contacts, query = '') {
    // Clear existing content
    contactsContainer.innerHTML = '';

    if (contacts.length === 0) {
      const emptyMessage = query
        ? '<p class="text-center text-muted">No contact found.</p>'
        : '<p class="text-center text-muted">No conversations yet.</p>';
      contactsContainer.innerHTML = emptyMessage;
      return;
    }

    // Build the list of contacts
    contacts.forEach((contact) => {
      const isMe = contact.sender_id === CURRENT_USER_ID;
      const prefix = isMe ? 'You: ' : '';
      const lastMessage = truncateText(contact.last_message ?? '');
      const timeLabel = formatTime(contact.last_message_time);

      const contactHtml = `
        <div class="contact-card d-flex align-items-center rounded p-1 my-1" data-id="${contact.user_id}" style="cursor: pointer;">
          <div class="me-2">
            <img class="contact-avatar img-fluid rounded-circle" src="https://i.pravatar.cc/?img=12" alt="Avatar">
          </div>
          <div class="d-flex flex-column">
            <span><strong>${contact.full_name}</strong></span>
            <small>
              ${prefix}${lastMessage}
              <span class="time-label mx-1">•</span>
              <span class="time-label">${timeLabel}</span>
            </small>
          </div>
        </div>
      `;
      // Use insertAdjacentHTML for better performance than innerHTML +=
      contactsContainer.insertAdjacentHTML('beforeend', contactHtml);
    });
  }

  /**
   * Searches for new users in the "Add Contact" modal
   */
  async function searchNewUsers(query) {
    if (!query) {
      modalResultsContainer.innerHTML =
        '<p class="text-center text-muted">Start typing to search...</p>';
      return;
    }

    try {
      const response = await fetch(
        `../api/messages/search_users.php?search=${encodeURIComponent(query)}`
      );
      const result = await response.json();

      if (result.status === 'success') {
        renderNewContactResults(result.data);
      } else {
        modalResultsContainer.innerHTML =
          '<p class="text-center text-muted">No contact found.</p>';
      }
    } catch (error) {
      modalResultsContainer.innerHTML =
        '<p class="text-center text-danger">Error searching contacts.</p>';
    }
  }

  function renderNewContactResults(users) {
    modalResultsContainer.innerHTML = '';

    if (users.length === 0) {
      modalResultsContainer.innerHTML =
        '<p class="text-center text-muted">No contact found.</p>';
      return;
    }

    users.forEach((user) => {
      const html = `
        <div class="contact-card d-flex align-items-center rounded p-2 my-1" data-id="${user.user_id}" style="cursor: pointer;">
          <div class="me-2">
            <img class="contact-avatar img-fluid rounded-circle" src="https://i.pravatar.cc/?img=12" alt="">
          </div>
          <div>
            <span>${user.full_name}</span>
            <small class="text-secondary d-block">@${user.username}</small>
          </div>
        </div>
      `;
      modalResultsContainer.insertAdjacentHTML('beforeend', html);
    });
  }

  // --- Event Listeners ---

  // Main search input with debounce logic
  mainSearchInput.addEventListener('input', (e) => {
    const query = e.target.value.trim();
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => fetchContacts(query), DEBOUNCE_DELAY_MS);
  });

  // Event delegation for clicking contact cards (both in list and modal)
  document.addEventListener('click', (e) => {
    const card = e.target.closest('.contact-card');
    if (card) {
      const contactId = card.dataset.id;
      window.location.href = `conversation.php?id=${contactId}`;
    }
  });

  // Modal search input with debounce
  modalSearchInput.addEventListener('input', (e) => {
    const query = e.target.value.trim();
    clearTimeout(modalSearchTimeout);
    modalSearchTimeout = setTimeout(
      () => searchNewUsers(query),
      DEBOUNCE_DELAY_MS
    );
  });

  // Bootstrap Modal Event Listeners (using native event names)
  addContactModal.addEventListener('shown.bs.modal', () => {
    modalSearchInput.focus();
  });

  addContactModal.addEventListener('hidden.bs.modal', () => {
    modalSearchInput.value = '';
    modalResultsContainer.innerHTML =
      '<p class="text-center text-muted">Start typing to search...</p>';
  });

  // --- Helper Functions ---

  function truncateText(text, maxLength = 30) {
    if (!text) return '';
    return text.length > maxLength
      ? `${text.substring(0, maxLength)}...`
      : text;
  }

  function formatTime(timeStr) {
    if (!timeStr) return '';

    const now = new Date();
    const msgDate = new Date(timeStr);
    const isToday = msgDate.toDateString() === now.toDateString();

    const yesterday = new Date();
    yesterday.setDate(now.getDate() - 1);
    const isYesterday = msgDate.toDateString() === yesterday.toDateString();

    if (isToday) {
      return msgDate.toLocaleTimeString([], {
        hour: '2-digit',
        minute: '2-digit',
      });
    } else if (isYesterday) {
      return 'Yesterday';
    } else if (msgDate.getFullYear() === now.getFullYear()) {
      return msgDate.toLocaleDateString([], { month: 'short', day: 'numeric' });
    }
    return msgDate.toLocaleDateString([], {
      month: 'short',
      day: 'numeric',
      year: 'numeric',
    });
  }

  // --- Background Polling ---
  setInterval(() => {
    // Only auto-refresh if the user isn't currently typing a search
    if (!mainSearchInput.value.trim()) {
      fetchContacts();
    }
  }, REFRESH_INTERVAL_MS);
});
