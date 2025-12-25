'use strict';

const state = {
  currentPage: 1,
  limit: 5,
  currentType: '',
  currentStatus: '',
};

const elements = {
  tableBody: document.getElementById('table-body'),
  activeCount: document.getElementById('active-count'),
  closedCount: document.getElementById('closed-count'),
  pendingCount: document.getElementById('pending-count'),
  paginationControl: document.getElementById('pagination-controls'),
  paginationInfo: document.getElementById('pagination-info'),
  activePage: document.querySelector('.app-main')?.dataset.activePage,
};

const donationForm = document.getElementById('create-donation-form');
const submitBtn = document.getElementById('submit-btn');

let isValid = true;

/* const donationRequests = requestsCount?.textContent?.trim() ?? '0'; */

// fetch data in donations page
async function fetchDonations(type, page = 1, status = '') {
  state.currentType = type;
  state.currentPage = page;
  state.currentStatus = status;

  const apiBackendUrl = `../api/donations/donations.php?type=${type}&page=${page}&status=${status}`;

  try {
    const response = await fetch(apiBackendUrl);

    const result = await response.json();

    if (!response.ok) throw new Error(result.message || 'Fetch data failed');

    const { stats, my_donations, donations, pagination } = result.data;

    if (type === 'fetch_donations') {
      if (elements.activeCount) elements.activeCount.textContent = stats.active;
      if (elements.closedCount) elements.closedCount.textContent = stats.closed;
      if (elements.pendingCount) {
        elements.pendingCount.textContent = stats.pending;
      }

      myDonations(my_donations);
    }

    if (
      ['active_donations', 'closed_donations', 'pending_donations'].includes(
        type
      )
    ) {
      loadDonationList(donations);
    }

    renderPagination(pagination.total_pages, pagination.current_page);
    renderPaginationInfo(pagination);
  } catch (error) {
    console.error(error);
  }
}

// donations page
function myDonations(donations) {
  if (!elements.tableBody) return;

  if (donations.length === 0) {
    elements.tableBody.innerHTML = `
      <tr>
        <td colspan="4" class="text-center text-muted">
          No donation data found
        </td>
      </tr>`;
    return;
  }

  elements.tableBody.innerHTML = donations
    .map((donation) => {
      const statusBadge =
        donation.status === 'Active' ? 'success' : 'secondary';

      return `
      <tr>
        <td>${donation.purpose}</td>
        <td>Php ${donation.amount}</td>
        <td>
          <span class="badge rounded-pill text-bg-${statusBadge}">
            ${donation.status}
          </span>
        </td>
        <td>
          <a class="btn btn-sm btn-primary"
          href="donation_details.php?id=${donation.donation_id}">View</a>
        </td>
      </tr>`;
    })
    .join('');
}

// fetch data in active, closed and pending(request) page
function loadDonationList(donations) {
  if (!elements.tableBody) return;

  elements.tableBody.innerHTML = `
    <tr>
      <td colspan="100%" class="text-md-center text-muted">
        Loading...
      </td>
    </tr>
  `;

  if (donations.length === 0) {
    elements.tableBody.innerHTML = `
      <tr>
        <td colspan="100%" class="text-md-center text-muted">
          No donation data found
        </td>
      </tr>`;
    return;
  }

  elements.tableBody.innerHTML = donations
    .map((donation) => {
      const statusBadge =
        donation.status === 'Active' ? 'success' : 'secondary';

      return `
      <tr>
        <td>${donation.purpose}</td>
        <td>${donation.start_date}</td>
        <td>${donation.due_date_formatted}</td>
        <td>
          <span class="badge rounded-pill text-bg-${statusBadge}">
            ${donation.status}
          </span>
        </td>
        <td>${donation.creator}</td>
        <td>${donation.approver}</td>
        <td>
          <a class="btn btn-sm btn-primary"
          href="donation_details.php?id=${donation.donation_id}"
          title="View">View</a>
        </td>
      </tr>`;
    })
    .join('');
}

function renderPagination(totalPages, currentPage) {
  if (!elements.paginationControl) return;

  if (totalPages <= 1) {
    elements.paginationControl.innerHTML = '';
    return;
  }

  const pages = getPaginationRange(currentPage, totalPages);
  let html = '';

  const getClickAction = (page) =>
    `fetchDonations('${state.currentType}', ${page}, '${state.currentStatus}')`;

  html += `
    <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
      <button class="page-link" onclick="${getClickAction(currentPage - 1)}">
        &lsaquo;
      </button>
    </li>
  `;

  pages.forEach((page) => {
    if (page === '...') {
      html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
    } else {
      html += `
        <li class="page-item ${currentPage === page ? 'active' : ''}">
          <button class="page-link" onclick="${getClickAction(page)}">
            ${page}
          </button>
        </li>
      `;
    }
  });

  html += `
    <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
      <button class="page-link" onclick="${getClickAction(currentPage + 1)}">
        &rsaquo;
      </button>
    </li>
  `;

  elements.paginationControl.innerHTML = html;
}

function getPaginationRange(current, last) {
  const delta = 1;
  const range = [];
  const rangeWithEllipsis = [];
  let l;

  for (let i = 1; i <= last; i++) {
    if (
      i === 1 ||
      i === last ||
      (i >= current - delta && i <= current + delta)
    ) {
      range.push(i);
    }
  }

  for (const i of range) {
    if (l) {
      if (i - l === 2) {
        rangeWithEllipsis.push(l + 1);
      } else if (i - l !== 1) {
        rangeWithEllipsis.push('...');
      }
    }
    rangeWithEllipsis.push(i);
    l = i;
  }

  return rangeWithEllipsis;
}

function renderPaginationInfo(pagination) {
  if (!elements.paginationInfo) return;

  const { current_page, total_pages } = pagination;

  if (total_pages === 0) {
    elements.paginationInfo.innerHTML = '';
    return;
  }

  elements.paginationInfo.innerHTML = `
    <small class="text-muted">
      Page <strong>${current_page}</strong> of <strong>${total_pages}</strong>
    </small>
  `;
}

// create/update donation validation
if (elements.activePage === 'create-update') {
  donationForm.addEventListener('submit', function (e) {
    e.preventDefault();

    submitBtn.disabled = true;

    const csrfToken = document.getElementById('csrf-token').value;
    const purpose = document.getElementById('purpose').value.trim();
    const description = document.getElementById('description').value.trim();
    const startDate = document.getElementById('start-date').value;
    const dueDate = document.getElementById('due-date').value;
    const type = document.getElementById('action').value;

    validateField('purpose', purpose === '', 'Purpose is required');
    validateField('description', description === '', 'Description is required');
    validateField(
      'start-date',
      startDate === null || startDate === '',
      'Start date is required'
    );
    validateField(
      'due-date',
      dueDate === null || dueDate === '',
      'Due date is required'
    );

    if (!isValid) {
      submitBtn.disabled = false;
      return;
    }

    const payload = {
      csrf_token: csrfToken,
      purpose: purpose,
      description: description,
      start_date: startDate,
      due_date: dueDate,
      type: type,
    };

    submitRequest(payload, type);
  });
}

// create/update donation send request
async function submitRequest(payload, type) {
  try {
    const response = await fetch(
      `../api/donations/donations.php?type=${type}`,
      {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
        },
        body: JSON.stringify(payload),
      }
    );

    const result = await response.json();

    if (!response.ok) {
      throw new Error('Error: ' + result.message);
    }

    if (result.status !== 'success') {
      Swal.fire('', result.message, result.status);
      return;
    }

    Swal.fire(
      'Success',
      result.message || 'Donation successfully created',
      'success'
    ).then(() => {
      donationForm.reset();
      resetField('purpose');
      resetField('description');
      resetField('start-date');
      resetField('due-date');
    });
  } catch (error) {
    console.error(error);
    Toast.fire({ icon: result.status, title: result.message }).then(() => {});
  } finally {
    submitBtn.disabled = false;
    isValid = true;
  }
}

// donation details page
if (elements.activePage === 'donation-details') {
  const donateForm = document.getElementById('donate-form');
  const donationId = document.querySelector('.app-main').dataset.donationId;
  const actionCon = document.getElementById('action-con');

  document.addEventListener('DOMContentLoaded', async function () {
    try {
      const response = await fetch(
        `../api/donations/donations.php?type=fetch_donors&donation_id=${donationId}`
      );

      const result = await response.json();

      if (!response.ok) {
        throw new Error(result.message || 'Fetching donors failed');
      }

      if (result.status !== 'success') {
        Swal.fire('', result.message, result.status);
        return;
      }

      const donors = result.data;

      if (!elements.tableBody) return;

      if (donors.length === 0) {
        elements.tableBody.innerHTML = `
          <tr>
            <td colspan="100%" class="text-center text-muted">
              No donors yet
            </td>
          </tr>
        `;
        return;
      }

      elements.tableBody.innerHTML = donors
        .map((donor) => {
          return `
          <tr>
            <td>${donor.donor_name}</td>
            <td>${donor.amount}</td>
            <td>
              <img class="img-thumbnail max-w-50"
              src="../storage/uploads/donations/${donor.proof_image}">
              </td>
            <td>
              <button class="btn btn-sm btn-primary">
                View
              </button>
            </td>
          </tr>`;
        })
        .join('');
    } catch (error) {
      console.log(error);
    }
  });

  if (donateForm) {
    const csrfToken = document.getElementById('csrf-token').value;
    const donateBtn = document.getElementById('donate-btn');
    const amount = document.getElementById('amount');
    const image = document.getElementById('image');
    const previewImg = document.getElementById('donation-img-preview');
    const amountFeedback =
      amount.parentElement.querySelector('.invalid-feedback');
    const imageFeedback =
      image.parentElement.querySelector('.invalid-feedback');

    const MAX_FILE_SIZE = 5 * 1024 * 1024;
    const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/jpg', 'image/png'];

    donateForm.addEventListener('submit', async function (e) {
      e.preventDefault();
      donateBtn.disabled = true;

      let isValid = true;

      amount.classList.remove('is-invalid', 'is-valid');
      image.classList.remove('is-invalid', 'is-valid');
      amountFeedback.textContent = '';
      previewImg.classList.add('d-none');

      const inputAmount = amount.value.trim();

      if (!inputAmount || inputAmount <= 0) {
        amountFeedback.textContent = 'Amount is required';
        amount.classList.add('is-invalid');
        isValid = false;
      } else {
        amount.classList.add('is-valid');
      }

      const file = image.files[0];

      if (!file) {
        imageFeedback.textContent = 'Please upload an image';
        image.classList.add('is-invalid');
        isValid = false;
      } else {
        if (!ALLOWED_MIME_TYPES.includes(file.type)) {
          imageFeedback.textContent = 'Invalid image type';
          image.classList.add('is-invalid');
          isValid = false;
        } else if (file.size > MAX_FILE_SIZE) {
          imageFeedback.textContent = 'File size is too large. Max is 5 MB';
          image.classList.add('is-invalid');
          isValid = false;
        } else {
          const objectUrl = URL.createObjectURL(file);
          previewImg.src = objectUrl;
          previewImg.classList.remove('d-none');
          image.classList.add('is-valid');

          previewImg.onload = () => URL.revokeObjectURL(objectUrl);
        }
      }

      if (!isValid) {
        donateBtn.disabled = false;
        return;
      }

      const formData = new FormData();
      formData.append('donation_id', donationId);
      formData.append('amount', inputAmount);
      formData.append('image', file);
      formData.append('type', 'donate');
      formData.append('csrf_token', csrfToken);

      try {
        const response = await fetch(
          '../api/donations/donations.php?type=donate',
          {
            method: 'POST',
            body: formData,
          }
        );

        const result = await response.json();

        if (!response.ok) {
          throw new Error(result.message || 'Donation failed');
        }

        if (result.status !== 'success') {
          Swal.fire('', result.message, result.status);
          return;
        }

        Swal.fire('', result.message, result.status).then(() => {
          /* donateForm.reset();
          amount.classList.remove('is-valid');
          image.classList.remove('is-valid');
          previewImg.src = ''; */
          window.location.reload();
        });
      } catch (error) {
        console.error(error);
      } finally {
        donateBtn.disabled = false;
      }
    });

    amount.addEventListener('input', function () {
      amount.classList.remove('is-invalid', 'is-valid');
      amountFeedback.textContent = '';
    });

    image.addEventListener('change', function () {
      const file = image.files[0];

      image.classList.remove('is-invalid', 'is-valid');
      imageFeedback.textContent = 'File size is too large. Max is 5 MB';
      previewImg.classList.add('d-none');

      if (file) {
        if (!ALLOWED_MIME_TYPES.includes(file.type)) {
          imageFeedback.textContent = 'Invalid image type';
          image.classList.add('is-invalid');
        } else if (file.size > MAX_FILE_SIZE) {
          imageFeedback.textContent = '';
          image.classList.add('is-invalid');
        } else {
          const objectUrl = URL.createObjectURL(file);
          previewImg.src = objectUrl;
          previewImg.classList.remove('d-none');
          image.classList.add('is-valid');
          previewImg.onload = () => URL.revokeObjectURL(objectUrl);
        }
      }
    });
  }

  if (actionCon) {
    actionCon.addEventListener('click', async function (e) {
      const btn = e.target.closest('button[data-action]');
      if (!btn) return;

      const action = btn.dataset.action;
      const actionMessage = action === 'approve' ? 'Approve' : 'Reject';

      const confirmationMessage = `Are you sure you want to ${actionMessage} this request?`;

      const confirmationResult = await Swal.fire({
        text: confirmationMessage,
        icon: 'question',
        showCancelButton: true,
        allowOutsideClick: false,
      });

      if (!confirmationResult.isConfirmed) {
        return;
      }

      btn.disabled = true;

      const payload = {
        action: action,
        donation_id: donationId,
      };

      try {
        const response = await fetch(
          '../api/donations/donations.php?type=handle_pending',
          {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              Accept: 'application/json',
            },
            body: JSON.stringify(payload),
          }
        );

        const result = await response.json();

        if (!response.ok) {
          throw new Error(response.message || 'Action failed');
        }

        if (result.status !== 'success') {
          Swal.fire('', result.message, result.status);
          return;
        }

        Swal.fire('', result.message, result.status).then(() =>
          window.location.reload()
        );
      } catch (error) {
        console.error(error);
      } finally {
        btn.disabled = false;
      }
    });
  }
}

// helper form validation function
function validateField(fieldId, condition, errorMessage) {
  const field = document.getElementById(fieldId);
  const feedBackElement = field.nextElementSibling;

  if (condition) {
    field.classList.add('is-invalid');
    field.classList.remove('is-valid');
    feedBackElement.textContent = errorMessage;
    feedBackElement.classList.add('d-block');
    isValid = false;
    return;
  } else {
    field.classList.remove('is-invalid');
    field.classList.add('is-valid');
    feedBackElement.classList.remove('d-block');
    isValid = true;
  }
}

// helper reset function
function resetField(fieldId) {
  const field = document.getElementById(fieldId);
  const feedBackElement = field.nextElementSibling;

  field.classList.remove('is-valid');
  field.classList.remove('is-invalid');
  feedBackElement.classList.remove('d-block');
}

// load data depending on active page
if (elements.activePage === 'donations') {
  document.addEventListener('DOMContentLoaded', () =>
    fetchDonations('fetch_donations', 1)
  );
}

if (elements.activePage === 'closed-donations') {
  document.addEventListener('DOMContentLoaded', () =>
    fetchDonations('closed_donations', 1, 'closed')
  );
}

if (elements.activePage === 'active-donations') {
  document.addEventListener('DOMContentLoaded', () =>
    fetchDonations('active_donations', 1, 'active')
  );
}

if (elements.activePage === 'pending-donations') {
  document.addEventListener('DOMContentLoaded', () =>
    fetchDonations('pending_donations', 1, 'pending')
  );
}
