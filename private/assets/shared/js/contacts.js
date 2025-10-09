$(document).ready(function () {
  fetchContacts();

  function fetchContacts() {
    $.ajax({
      url: '../../api/contacts.php',
      method: 'GET',
      dataType: 'json',
      success: (res) => {
        if (res.status === 'success') {
          renderContacts(res.data);
        } else {
          console.error(res.message);
        }
      },
      error: function (xhr, status, error) {
        console.error('Error fetching contacts:', error);
      },
    });
  }

  function renderContacts(contacts) {
    const $body = $('#contacts-body');
    $body.empty();

    if (contacts.length === 0) {
      $body.html('<p class="text-center text-muted">No conversations yet.</p>');
      return;
    }

    contacts.forEach((contact) => {
      const prefix = contact.sender_id === CURRENT_USER_ID ? 'You: ' : '';
      const lastMessage = truncateText(contact.last_message ?? '');

      const html = `
        <div class="contact-card d-flex align-items-center rounded p-1 my-1" data-id="${
          contact.user_id
        }">
          <div class="mr-2">
            <img class="contact-avatar img-fluid rounded-circle" src="https://i.pravatar.cc/?img=12" alt="">
          </div>

          <div class="d-flex flex-column">
            <span><strong>${contact.full_name}</strong></span>
            <small>
              ${prefix}${lastMessage}
              <span class="text-secondary mx-1">•</span>
              <span class="text-secondary">${formatTime(
                contact.last_message_time
              )}</span>
            </small>
          </div>
        </div>
      `;

      $body.append(html);
    });
  }

  $(document).on('click', '.contact-card', function () {
    const contactID = $(this).data('id');
    window.location.href = `messages.php?id=${contactID}`;
  });

  function truncateText(text, maxLength = 30) {
    if (!text) return '';
    return text.length > maxLength
      ? text.substring(0, maxLength) + '...'
      : text;
  }

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
});
