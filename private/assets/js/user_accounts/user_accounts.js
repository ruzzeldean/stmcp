'use strict';

const state = { currentPage: 1, limit: 5 };

const elements = {
  tableBody: document.getElementById('table-body'),
  paginationControl: document.getElementById('pagination-controls'),
  paginationInfo: document.getElementById('pagination-info'),
  activePage: document.querySelector('.app-main')?.dataset.activePage,
  csrfToken: document.querySelector('.app-main')?.dataset.csrfToken,
};

async function fetchUserAccounts(page = 1) {
  if (!Number.isInteger(page) || page < 1) return;
  state.currentPage = page;

  const apiBackendUrl = `../api/user_accounts/user_accounts.php?page=${page}`;

  try {
    const response = await fetch(apiBackendUrl, {
      method: 'GET',
      headers: {
        Accept: 'application/json',
      },
    });

    const result = await response.json();

    if (!response.ok) {
      throw new Error(result.message || 'Fetching user accounts failed');
    }

    if (result.status !== 'success') {
      Swal.fire('', result.message, result.status);
      return;
    }

    renderTable(result.data.user_accounts);
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

function renderTable(users) {
  if (!elements.tableBody) return;

  if (!Array.isArray(users) || users.length === 0) {
    elements.tableBody.innerHTML = `
      <tr>
        <td colspan="100%" class="text-md-center text-muted">
          No user data found
        </td>
      </tr>`;
    return;
  }

  elements.tableBody.innerHTML = users
    .map((user) => {
      const firstName = user.first_name ?? '';
      const lastName = user.last_name ?? '';
      const username = user.username ?? '';
      const role = user.role_name ?? '';
      const rawStatus = user.status ?? '';
      const updatedAt = user.updated_at ?? '';

      const statusConfig = {
        Active: { class: 'text-bg-success', label: 'Active' },
        Disabled: { class: 'text-bg-secondary', label: 'Disabled' },
      };

      const status = statusConfig[String(rawStatus)] || {
        class: 'text-bg-info',
        label: String(rawStatus || 'Unknown'),
      };

      return `
      <tr>
        <td>${escapeHtml(firstName)}</td>
        <td>${escapeHtml(lastName)}</td>
        <td>${escapeHtml(username)}</td>
        <td>${escapeHtml(role)}</td>
        <td>
          <span class="badge rounded-pill ${status.class}">
            ${escapeHtml(status.label)}
          </span>
        </td>
        <td>${escapeHtml(updatedAt)}</td>
        <td>
          <a class="btn btn-sm btn-primary" title="Update"
          href="update_user_account.php?id=${encodeURIComponent(user.user_id)}">
            Update
          </a>
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

    fetchUserAccounts(page);
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

document.addEventListener('DOMContentLoaded', () => fetchUserAccounts());

elements.tableBody.addEventListener('click', async function (e) {
  const disableBtn = e.target.closest('.btn-secondary');
  if (!disableBtn) return;

  const userId = disableBtn.dataset.userId;

  try {
    const response = await fetch('../api/user_accounts/user_accounts.php', {
      method: 'user',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        action: 'disable',
        user_id: userId,
      }),
    });

    const result = await response.json();

    if (!response.ok) {
      throw new Error('Action failed');
    }

    Toast.fire({ icon: result.status, firstName: result.message });
    fetchUserAccounts();
  } catch (error) {
    console.error(error);
  }
});
