'use strict';

const state = { currentPage: 1, limit: 5 };

const elements = {
  tableBody: document.getElementById('table-body'),
  paginationControl: document.getElementById('pagination-controls'),
  paginationInfo: document.getElementById('pagination-info'),
  updateMemberForm: document.getElementById('update-member-form'),
  activePage: document.querySelector('.app-main')?.dataset.activePage,
  csrfToken: document.querySelector('.app-main')?.dataset.csrfToken,
};

async function fetchMembers(page = 1) {
  if (!Number.isInteger(page) || page < 1) return;
  state.currentPage = page;

  const apiBackendUrl = `../api/official_members/official_members.php?page=${page}`;

  try {
    const response = await fetch(apiBackendUrl, {
      method: 'GET',
      headers: {
        Accept: 'application/json',
      },
    });

    const result = await response.json();

    if (!response.ok) {
      throw new Error(result.message || 'Fetching member details failed');
    }

    if (result.status !== 'success') {
      Swal.fire('', result.message, result.status);
      return;
    }

    renderTable(result.data.members_data);
    renderPagination(
      result.data.pagination.total_pages,
      result.data.pagination.current_page
    );
    renderPaginationInfo(result.data.pagination);
  } catch (error) {
    console.error(error);
    Swal.fire('Error', error.message, 'error');
  }
}

function renderTable(members) {
  if (!elements.tableBody) return;

  if (!Array.isArray(members) || members.length === 0) {
    elements.tableBody.innerHTML = `
      <tr>
        <td colspan="100%" class="text-md-center text-muted">
          No member data found
        </td>
      </tr>`;
    return;
  }

  elements.tableBody.innerHTML = members
    .map((member) => {
      const esc = (key) => escapeHtml(member[key] ?? '');

      return `
      <tr>
        <td>
          <a class="btn btn-sm btn-primary"
          href="update_member.php?id=${encodeURIComponent(
            String(member.person_id || '')
          )}"
          title="Update">Update</a>

          <button class="delete-btn btn btn-sm btn-outline-danger"
          data-id="${String(member.person_id || '')}"
          title="Delete">Delete</button>
        </td>
        <td>${esc('first_name')}</td>
        <td>${esc('last_name')}</td>
        <td>${esc('middle_name')}</td>
        <td>${esc('chapter_name')}</td>
        <td>${esc('date_of_birth')}</td>
        <td>${esc('civil_status')}</td>
        <td>${esc('blood_type')}</td>
        <td>${esc('home_address')}</td>
        <td>${esc('phone_number')}</td>
        <td>${esc('email')}</td>
        <td>${esc('emergency_contact_name')}</td>
        <td>${esc('emergency_contact_number')}</td>
        <td>${esc('occupation')}</td>
        <td>${esc('license_number')}</td>
        <td>${esc('motorcycle_brand')}</td>
        <td>${esc('motorcycle_model')}</td>
        <td>${esc('sponsor')}</td>
        <td>${esc('other_club_affiliation')}</td>
        <td>${esc('date_joined')}</td>
        <td>${esc('created_at')}</td>
        <td>${esc('updated_at')}</td>
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

  html += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
    <button class="page-link" data-page="${Math.max(1, currentPage - 1)}">
      &lsaquo;
    </button>
  </li>`;

  pages.forEach((page) => {
    if (page === '...') {
      html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
    } else {
      html += `<li class="page-item ${
        currentPage === page ? 'active disabled' : ''
      }">
        <button class="page-link" data-page="${page}">${page}</button>
      </li>`;
    }
  });

  html += `
    <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
      <button class="page-link" data-page="${Math.min(
        totalPages,
        currentPage + 1
      )}">
        &rsaquo;
      </button>
    </li>
  `;

  elements.paginationControl.innerHTML = html;
}

if (elements.paginationControl) {
  elements.paginationControl.addEventListener('click', (e) => {
    const item = e.target.closest('.page-item');
    if (!item || item.classList.contains('disabled')) return;

    const btn = item.querySelector('button[data-page]');
    if (!btn) return;

    const page = Number(btn.dataset.page);
    if (!Number.isInteger(page) || page < 1) return;

    fetchMembers(page);
  });
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

async function handleDelete(e) {
  const deleteBtn = e.target.closest('.btn-outline-danger');
  if (!deleteBtn) return;

  const personId = deleteBtn.dataset.id;
  const csrfToken = elements.csrfToken;

  const confirmationResult = await Swal.fire({
    text: 'Are you sure you want to delete this record?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc3545',
    confirmButtonText: 'Yes',
  });

  if (!confirmationResult.isConfirmed) return;

  const passwordResult = await Swal.fire({
    title: 'Confirm deletion',
    input: 'password',
    inputPlaceholder: 'Enter your password',
    showCancelButton: true,
    showLoaderOnConfirm: true,
    allowOutsideClick: () => !Swal.isLoading(),

    preConfirm: async (password) => {
      if (!password) {
        Swal.showValidationMessage('Password is required');
        return;
      }

      try {
        const response = await fetch(
          '../api/official_members/official_members.php',
          {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              Accept: 'application/json',
            },
            body: JSON.stringify({
              csrf_token: csrfToken,
              action: 'deleteMember',
              person_id: personId,
              password,
            }),
          }
        );

        const result = await response.json();

        if (!response.ok || result.status !== 'success') {
          throw new Error(result.message || 'Incorrect message');
        }

        return result;
      } catch (error) {
        Swal.showValidationMessage(error.message);
        return false;
      }
    },
  });

  if (!passwordResult.isConfirmed) return;

  await Swal.fire('Deleted!', passwordResult.value.message, 'success');

  fetchMembers();
}

if (elements.activePage === 'official-members') {
  const initialize = () => fetchMembers(1);
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initialize);
  } else {
    initialize();
  }

  if (elements.tableBody) {
    const tableBody = elements.tableBody;

    tableBody.addEventListener('click', handleDelete);
  }
}

/* Update form */

const FIELD_CONFIG = [
  { id: 'first-name', required: true, msg: 'First name is required' },
  { id: 'last-name', required: true, msg: 'Last name is required' },
  { id: 'date-of-birth', required: true, msg: 'Date of birth is required' },
  { id: 'civil-status', required: true, msg: 'Civil status is required' },
  { id: 'blood-type', required: true, msg: 'Blood type is required' },
  { id: 'home-address', required: true, msg: 'Home address is required' },
  { id: 'phone-number', required: true, msg: 'Phone number is required' },
  { id: 'email', required: true, msg: 'Email is required' },
  {
    id: 'emergency-contact-name',
    required: true,
    msg: 'Contact person is required',
  },
  {
    id: 'emergency-contact-number',
    required: true,
    msg: 'Emergency contact number is required',
  },
  { id: 'occupation', required: true, msg: 'Occupation is required' },
  { id: 'license-number', required: true, msg: 'License number is required' },
  {
    id: 'motorcycle-brand',
    required: true,
    msg: 'Motorcycle brand is required',
  },
  {
    id: 'motorcycle-model',
    required: true,
    msg: 'Motorcycle model is required',
  },
  { id: 'chapter-id', required: true, msg: 'Chapter is required' },
  { id: 'date-joined', required: true, msg: 'Date joined is required' },
];

const updateMemberForm = document.getElementById('update-member-form');
const updateBtn = document.getElementById('update-btn');

async function handleFormSubmit(e) {
  e.preventDefault();

  updateBtn.disabled = true;

  const isFormValid = validateAllFields();

  if (!isFormValid) {
    Toast.fire({
      icon: 'warning',
      title: 'Please fill all required fields correctly',
    });
    updateBtn.disabled = false;
    return;
  }

  const formData = new FormData(updateMemberForm);
  const payload = Object.fromEntries(formData);

  toggleLoading(true);

  try {
    const response = await fetch(
      '../api/official_members/official_members.php',
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
      throw new Error(result.message || 'Server error occured');
    }

    if (result.status !== 'success') {
      Toast.fire({ icon: result.status, title: result.message });
      toggleLoading(false);
      updateBtn.disabled = false;
      return;
    }

    toggleLoading(false);
    Swal.fire({
      icon: result.status || 'success',
      text: result.message || 'Changes saved',
    }).then(() => {
      location.reload();
    });
  } catch (error) {
    console.error('Submission Error:', error);
    Toast.fire({
      icon: 'error',
      message: 'Unable to connect to the server. Please try again',
    });

    toggleLoading(false);
    updateBtn.disabled = false;
  }
}

function validateAllFields() {
  let isValid = true;

  FIELD_CONFIG.forEach((field) => {
    if (!field.required) return;

    const input = document.getElementById(field.id);
    const value = input.value.trim();

    if (!toggleErrorState(input, value === '', field.msg)) {
      isValid = false;
    }
  });

  return isValid;
}

function toggleErrorState(element, isError, message) {
  const feedback = element.nextElementSibling;

  if (isError) {
    element.classList.add('is-invalid');
    element.classList.remove('is-valid');

    if (feedback) {
      feedback.textContent = message;
      feedback.classList.add('d-block');
    }
    return false;
  } else {
    element.classList.remove('is-invalid');
    element.classList.add('is-valid');
    if (feedback) feedback.classList.remove('d-block');
    return true;
  }
}

function toggleCheckboxError(element, isError, message) {
  const formCheck = element.closest('.form-check');
  const feedback = formCheck.querySelector('.invalid-feedback');

  if (isError) {
    if (feedback) {
      feedback.textContent = message;
      feedback.classList.add('d-block');
    }
    return false;
  } else {
    if (feedback) feedback.classList.remove('d-block');
    return true;
  }
}

function handleFieldInteraction(e) {
  const target = e.target;

  if (target.matches('.form-control, .form-select')) {
    target.classList.remove('is-invalid', 'is-valid');
    const feedback = target.nextElementSibling;
    if (feedback) feedback.classList.remove('d-block');
  }

  if (target.matches('.form-check-input')) {
    const formCheck = target.closest('.form-check');
    const feedback = formCheck?.querySelector('.invalid-feedback');
    if (feedback) feedback.classList.remove('d-block');
  }
}

function toggleLoading(isActive) {
  const pageOverlay = document.getElementById('page-overlay');
  const formElements = document.querySelectorAll('input, select, button');

  if (pageOverlay) pageOverlay.classList.toggle('d-none', !isActive);
  formElements.forEach((element) => (element.disabled = isActive));
}

if (elements.activePage === 'update-member') {
  updateMemberForm.addEventListener('submit', handleFormSubmit);
  updateMemberForm.addEventListener('input', handleFieldInteraction);
  updateMemberForm.addEventListener('change', handleFieldInteraction);
}
