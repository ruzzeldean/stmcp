$(document).ready(function () {
  const REFREST_INTERVAL = 5000;
  let searchTimeout = null;
  let currentSearch = '';

  fetchContacts();

  function fetchContacts(query = '') {
    $.ajax({
      url: '../../api/contacts.php',
      method: 'GET',
      dataType: 'json',
      data: { search: query },
      success: (res) => {
        if (res.status === 'success') {
          currentSearch = query;
          renderContacts(res.data, query);
        } else {
          console.error(res.message);
        }
      },
      error: function (xhr, status, error) {
        console.error('Error fetching contacts:', error);
      },
    });
  }

  $('#search-contacts').on('input', function () {
    const query = $(this).val().trim();

    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
      fetchContacts(query);
    }, 300);
  });

  function renderContacts(contacts, query = '') {
    const $body = $('#contacts-body');
    $body.empty();

    if (contacts.length === 0) {
      const message = query
        ? '<p class="text-center text-muted">No contact found.</p>'
        : '<p class="text-center text-muted">No conversations yet.</p>';

      $body.html(message);
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
        minute: '2-digit',
      });
    } else if (isYesterday) {
      return 'Yesterday';
    } else if (sameYear) {
      return msgDate.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
      });
    } else {
      return msgDate.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
      });
    }
  }

  // logic for add new contact modal

  let modalSearchTimeout = null;

  $('#add-contact-modal').on('show.bs.modal', function () {
    $('#search-new-contacts').trigger('focus');
  });

  $('#add-contact-modal').on('hide.bs.modal', function () {
    if (document.activeElement) {
      document.activeElement.blur();
    }
  });

  $('#add-contact-modal').on('hidden.bs.modal', function () {
    $('#search-new-contacts').val('');
  });

  $('#new-contact-results').html(
    '<p class="text-center text-muted">Start typing to search...</p>'
  );

  $('#search-new-contacts').on('input', function () {
    const query = $(this).val().trim();

    clearTimeout(modalSearchTimeout);
    modalSearchTimeout = setTimeout(() => {
      if (!query) {
        $('#new-contact-results').html(
          '<p class="text-center text-muted">Start typing to search...</p>'
        );
        return;
      }

      $.ajax({
        url: '../../api/search_users.php',
        method: 'GET',
        dataType: 'json',
        data: { search: query },
        success: (res) => {
          if (res.status === 'success') {
            renderNewContactResults(res.data);
          } else {
            $('#new-contact-results').html(
              '<p class="text-center text-muted">No contact found.</p>'
            );
          }
        },
        error: () => {
          $('#new-contact-results').html(
            '<p class="text-center text-danger">Error searching contacts.</p>'
          );
        },
      });
    }, 300);
  });

  function renderNewContactResults(users) {
    const $results = $('#new-contact-results');
    $results.empty();

    if (users.length === 0) {
      $results.html('<p class="text-center text-muted">No contact found.</p>');
      return;
    }

    users.forEach((user) => {
      /* const imgSrc = user.profile_photo
        ? `../../uploads/${user.profile_photo}`
        : 'https://i.pravatar.cc/?img=12'; */
      const imgSrc = 'https://i.pravatar.cc/?img=12';

      const html = `
        <div class="contact-card d-flex align-items-center rounded p-2 my-1" data-id="${user.user_id}">
          <div class="mr-2">
            <img class="contact-avatar img-fluid rounded-circle" src="${imgSrc}" alt="">
          </div>

          <div class="">
            <span>${user.full_name}</span>
            <small class="text-secondary d-block">@${user.username}</small>
          </div>
        </div>
      `;
      $results.append(html);
    });
  }

  $('#add-contact-modal').on('hide.bs.modal', function () {
    const activeEl = document.activeElement;
    if ($(this).has(activeEl).length) {
      activeEl.blur();
    }
  });

  setInterval(() => {
    const query = $('#search-contacts').val().trim();

    if (!query) {
      fetchContacts();
    }
  }, REFREST_INTERVAL);
});
