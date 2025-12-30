'use strict';

const state = { currentPage: 1, limit: 5 };

const tableBody = document.getElementById('table-body');
const paginationControl = document.getElementById('pagination-controls');
const paginationInfo = document.getElementById('pagination-info');
const csrfToken = document.querySelector('.app-main')?.dataset.csrfToken;

tableBody.addEventListener('click', handleApprove);
/* tableBody.addEventListener('click', handleReject); */

async function fetchApirants(page = 1) {
  if (!Number.isInteger(page) || page < 1) return;
  state.currentPage = page;

  const apiBackendUrl = `../api/aspirants/aspirants.php?page=${page}`;

  try {
    const response = await fetch(apiBackendUrl, {
      method: 'GET',
      headers: {
        Accept: 'application/json',
      },
    });

    const result = await response.json();

    if (!response.ok) {
      throw new Error(result.message || 'Fetching aspirants failed');
    }

    if (result.status !== 'success') {
      Swal.fire('', result.message, result.status);
      return;
    }

    renderTable(result.data.aspirants_list);
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

async function handleApprove(e) {
  const submitBtn = e.target.closest('.btn-success, .btn-danger');
  if (!submitBtn) return;

  const personId = submitBtn.dataset.id;
  const action = submitBtn.dataset.action;

  const confirmationResult = await Swal.fire({
    text: `Are you sure you want to ${
      action === 'approve' ? 'Approve' : 'Reject'
    } this aspirant?`,
    icon: `${action === 'approve' ? 'question' : 'warning'}`,
    showCancelButton: true,
    confirmButtonColor: `${action === 'approve' ? '#0d6efd' : '#dc3545'}`,
    confirmButtonText: 'Yes',
  });

  if (!confirmationResult.isConfirmed) return;

  const passwordResult = await Swal.fire({
    title: `Confirm ${action === 'approve' ? 'approval' : 'rejection'}`,
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
          `../api/aspirants/aspirants.php?action=${action}`,
          {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              Accept: 'application/json',
            },
            body: JSON.stringify({
              csrf_token: csrfToken,
              action: action,
              person_id: personId,
              password,
            }),
          }
        );

        const result = await response.json();

        if (!response.ok || result.status !== 'success') {
          throw new Error(result.message || 'Incorrect password');
        }

        return result;
      } catch (error) {
        Swal.showValidationMessage(error.message);
        return false;
      }
    },
  });

  if (!passwordResult.isConfirmed) return;

  await Swal.fire('', passwordResult.value.message, 'success');

  fetchApirants();
}

function renderTable(aspirants) {
  if (!tableBody) return;

  if (!Array.isArray(aspirants) || aspirants.length === 0) {
    tableBody.innerHTML = `
      <tr>
        <td colspan="100%" class="text-muted">
          No aspirants yet
        </td>
      </tr>`;
    return;
  }

  tableBody.innerHTML = aspirants
    .map((aspirant) => {
      const esc = (key) => escapeHtml(aspirant[key] ?? '');

      return `
      <tr>
        <td>
          <button class="approve-btn btn btn-sm btn-success"
          data-action="approve" data-id="${String(aspirant.person_id || '')}"
          title="Approve">Approve</button>

          <button class="reject-btn btn btn-sm btn-danger"
          data-action="reject" data-id="${String(aspirant.person_id || '')}"
          title="Reject">Reject</button>
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
  if (!paginationControl) return;

  if (totalPages <= 1) {
    paginationControl.innerHTML = '';
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

  paginationControl.innerHTML = html;
}

if (paginationControl) {
  paginationControl.addEventListener('click', (e) => {
    const item = e.target.closest('.page-item');
    if (!item || item.classList.contains('disabled')) return;

    const btn = item.querySelector('button[data-page]');
    if (!btn) return;

    const page = Number(btn.dataset.page);
    if (!Number.isInteger(page) || page < 1) return;

    fetchApirants(page);
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
  if (!paginationInfo) return;

  const { current_page, total_pages } = pagination;

  if (total_pages === 0) {
    paginationInfo.innerHTML = '';
    return;
  }

  paginationInfo.innerHTML = `
    <small class="text-muted">
      Page <strong>${current_page}</strong> of <strong>${total_pages}</strong>
    </small>
  `;
}

const initialize = () => fetchApirants(1);
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initialize);
} else {
  initialize();
}
