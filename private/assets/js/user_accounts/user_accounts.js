'use strict';

const tableBody = document.getElementById('table-body');

/**
 * Generates an array of page numbers with ellipsis.
 * @param {number} current - Current active page
 * @param {number} last - Total number of pages
 * @returns {Array} List of page numbers and '...' strings
 */
const getPaginationRange = (current, last) => {
  const delta = 1; // Number of pages to show before and after current
  const left = current - delta;
  const right = current + delta + 1;
  const range = [];
  const rangeWithDots = [];
  let l;

  for (let i = 1; i <= last; i++) {
    // Always include first, last, and pages within the delta range
    if (i === 1 || i === last || (i >= left && i < right)) {
      range.push(i);
    }
  }

  for (const i of range) {
    if (l) {
      if (i - l === 2) {
        rangeWithDots.push(l + 1);
      } else if (i - l !== 1) {
        rangeWithDots.push('...');
      }
    }
    rangeWithDots.push(i);
    l = i;
  }

  return rangeWithDots;
};

/**
 * Fetches data from PHP and renders the table and pagination.
 * @param {Event|null} event - The click event (if applicable)
 * @param {number} page - The page number to load
 */
async function loadTableData(event, page = 1) {
  // Check if event exists and is an actual Event object before calling preventDefault
  if (event && typeof event.preventDefault === 'function') {
    event.preventDefault();
  }

  try {
    const response = await fetch(
      `../api/user_accounts/user_accounts.php?page=${page}`
    );
    const result = await response.json();

    if (result.status === 'success') {
      renderTable(result.data.user_accounts);
      renderPagination(result.data.total_pages, result.data.current_page);
    }
  } catch (error) {
    console.error('Error loading data:', error);
  }
}

function renderTable(users) {
  tableBody.innerHTML = users
    .map((user) => {
      const btnColor = user.status === 'Active' ? 'success' : 'secondary';

      return `
          <tr>
            <td>${user.first_name}</td>
            <td>${user.last_name}</td>
            <td>${user.username}</td>
            <td>${user.role_name}</td>
            <td>${user.status}</td>
            <td>${user.updated_at}</td>
            <td>
              <a class="btn btn-primary" href="update_user_account.php?id=${user.user_id}" title="Update">
                <i class="fa-solid fa-user-pen"></i>
              </a>
              <button class="btn btn-secondary" data-user-id="${user.user_id}" title="Disable">
                <i class="fa-solid fa-user-slash"></i>
              </button>
            </td>
          </tr>
        `;
    })
    .join('');
}

function renderPagination(totalPages, currentPage) {
  const nav = document.getElementById('paginationControls');
  const pages = getPaginationRange(currentPage, totalPages);

  let html = '';

  // Previous Button
  html += `
    <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
      <a class="page-link" href="#" onclick="loadTableData(event, ${
        currentPage - 1
      })">&lsaquo;</a>
    </li>
  `;

  // Page Numbers & Ellipsis
  pages.forEach((page) => {
    if (page === '...') {
      html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
    } else {
      html += `
        <li class="page-item ${currentPage === page ? 'active' : ''}">
          <a class="page-link" href="#" onclick="loadTableData(event, ${page})">${page}</a>
        </li>
      `;
    }
  });

  // Next Button - FIXED: Added 'event' argument here
  html += `
    <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
      <a class="page-link" href="#" onclick="loadTableData(event, ${
        currentPage + 1
      })">&rsaquo;</a>
    </li>
  `;

  nav.innerHTML = html;
}

// Initial load - Pass null for the event since there isn't one on page load
document.addEventListener('DOMContentLoaded', () => loadTableData(null, 1));

tableBody.addEventListener('click', async function (e) {
  const disableBtn = e.target.closest('.btn-secondary');
  const userId = disableBtn.dataset.userId;

  try {
    const response = await fetch('../api/user_accounts/user_accounts.php', {
      method: 'POST',
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

    Toast.fire({ icon: result.status, title: result.message });
    loadTableData(null, 1);
  } catch (error) {
    console.error(error);
  }
});