'use strict';

// store current page (localstorage)

const state = { currentPage: 1, limit: 5 };

const elements = {
  tableBody: document.getElementById('table-body'),
  paginationControl: document.getElementById('pagination-controls'),
  paginationInfo: document.getElementById('pagination-info'),
  updatepostForm: document.getElementById('update-post-form'),
  userName: document.getElementById('table-body')?.dataset?.userName ?? null,
  userRole: (
    document.getElementById('table-body')?.dataset?.userRole ?? ''
  ).toLowerCase(),
  csrfToken: document.querySelector('.app-main')?.dataset?.csrfToken ?? null,
};

let currentPost = [];

async function fetchPosts(page = 1) {
  if (!Number.isInteger(page) || page < 1) return;
  state.currentPage = page;

  const apiBackendUrl = `../api/posts/posts.php?page=${page}`;

  try {
    const response = await fetch(apiBackendUrl, {
      method: 'GET',
      headers: {
        Accept: 'application/json',
      },
    });

    const result = await response.json();

    if (!response.ok) {
      throw new Error(result.message || 'Fetching post details failed');
    }

    if (result.status !== 'success') {
      Swal.fire('', result.message, result.status);
      return;
    }

    currentPost = result.data.posts;
    renderTable(result.data.posts);
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

function renderTable(posts) {
  if (!elements.tableBody) return;

  if (!Array.isArray(posts) || posts.length === 0) {
    elements.tableBody.innerHTML = `
      <tr>
        <td colspan="100%" class="text-md-center text-muted">
          No post data found
        </td>
      </tr>`;
    return;
  }

  elements.tableBody.innerHTML = posts
    .map((post) => {
      const title = post.title ?? '';
      const createdBy = post.created_by ?? '';
      const createdAt = post.created_at ?? '';
      const imagePath = post.image_path ?? '';
      const rawCategory = post.category ?? '';
      const rawStatus = post.status ?? '';

      const categoryConfig = {
        Announcement: { class: 'text-bg-warning', label: 'Announcement' },
        Upcoming: { class: 'text-bg-info', label: 'Upcoming' },
        'Past Event': { class: 'text-bg-success', label: 'Past Event' },
      };

      const category = categoryConfig[String(rawCategory)] || {
        class: 'text-bg-secondary',
        label: String(rawCategory || 'Unspecified'),
      };

      const statusConfig = {
        Pending: { class: 'text-bg-secondary', label: 'Pending' },
        Published: { class: 'text-bg-success', label: 'Published' },
        Rejected: { class: 'text-bg-danger', label: 'Rejected' },
      };

      const status = statusConfig[String(rawStatus)] || {
        class: 'text-bg-info',
        label: String(rawStatus || 'Unknown'),
      };

      const display = elements.userRole === 'moderator' ? 'd-none' : '';
      const editBtn =
        elements.userName && elements.userName === createdBy ? '' : 'd-none';
      const alreadyApproved = rawStatus === 'Published' ? 'd-none' : '';
      const alreadyRejected = rawStatus === 'Rejected' ? 'd-none' : '';

      return `
      <tr>
        <td>${escapeHtml(title)}</td>
        <td>
          <span class="badge rounded-pill ${category.class}">
            ${escapeHtml(category.label)}
          </span>
        </td>
        <td>
          <img class="img-thumbnail max-w-50" src="../../public/assets/${escapeHtml(
            imagePath
          )}" alt="${escapeHtml(title)}">
        </td>
        <td>
          <span class="badge rounded-pill ${status.class}">
            ${escapeHtml(status.label)}
          </span>
        </td>
        <td>${escapeHtml(createdBy)}</td>
        <td>${escapeHtml(createdAt)}</td>
        <td>
          <button class="preview-btn btn btn-primary" type="button"
          data-bs-toggle="modal" data-bs-target="#preview-modal"
          data-post-id="${post.post_id}" title="Preview">
            <i class="fa-solid fa-eye"></i>
          </button>
          
          <button class="approve-btn btn btn-success ${display} ${alreadyApproved}"
          data-post-id="${post.post_id}" title="Approve">
            <i class="fa-solid fa-check"></i>
          </button>

          <button class="reject-btn btn btn-danger ${display} ${alreadyRejected}"
          data-post-id="${post.post_id}" title="Reject">
            <i class="fa-solid fa-xmark"></i>
          </button>

          <a class="edit-btn btn btn-info ${editBtn}"
          href="update_post.php?id=${encodeURIComponent(
            post.post_id
          )}" title="Edit">
            <i class="fa-solid fa-pen-to-square"></i>
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

    fetchPosts(page);
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

const initialize = () => fetchPosts();
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initialize);
} else {
  initialize();
}

// preview btn
document.addEventListener('click', function (e) {
  const previewBtn = e.target.closest('.preview-btn');
  if (!previewBtn) return;

  const postId = previewBtn.dataset.postId;
  const post = currentPost.find((p) => p.post_id == postId);

  if (!post) {
    Swal.fire('Error', 'Post not found', 'error');
    return;
  }

  const categoryConfig = {
    Announcement: { class: 'text-bg-warning', label: 'Announcement' },
    Upcoming: { class: 'text-bg-info', label: 'Upcoming' },
    'Past Event': { class: 'text-bg-success', label: 'Past Event' },
  };

  const category = categoryConfig[String(post.category)] || {
    class: 'text-bg-secondary',
    label: post.category,
  };

  const modalBody = document.querySelector('.modal-body');
  if (!modalBody) return;

  modalBody.innerHTML = `
    <h3 id="post-title" class="mb-1">${escapeHtml(post.title)}</h3>

    <div>
      <span id="post-category"
      class="badge rounded-pill ${category.class}">
        ${escapeHtml(category.label)}
      </span> |
      <small id="post-date" class="badge text-muted">${escapeHtml(
        post.created_at
      )}</small>
    </div>

    <img id="post-image" class="img-fluid rounded my-3"
    src="../../public/assets/${escapeHtml(post.image_path)}" alt="${escapeHtml(
    post.title
  )}">

    <div class="ws-pre-wrap">${escapeHtml(post.content)}</div>
  `;
});

// aprove btn
document.addEventListener('click', function (e) {
  const approveBtn = e.target.closest('.approve-btn');

  if (!approveBtn) return;

  const postId = approveBtn.dataset.postId;

  Swal.fire({
    text: 'Are you sure you want to approve this post?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#0d6efd',
    confirmButtonText: 'Yes',
    showLoaderOnConfirm: true,
    preConfirm: async () => {
      try {
        const response = await fetch('../api/posts/posts.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            action: 'approvePost',
            post_id: postId,
          }),
        });

        const result = await response.json();

        if (!response.ok) {
          throw new Error(result.message || 'Approving post failed');
        }

        if (result.status !== 'success') {
          Swal.fire('', result.message, result.status);
          return;
        }

        Swal.fire('Approved', result.message, result.status).then(() => {
          fetchPosts();
        });
      } catch (error) {
        console.error(error);
        Swal.fire('', error.message, 'error');
      }
    },
  });
});

// reject btn
document.addEventListener('click', function (e) {
  const rejectBtn = e.target.closest('.reject-btn');
  if (!rejectBtn) return;

  const postId = rejectBtn.dataset.postId;
  Swal.fire({
    text: 'Are you sure you want to reject this post?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#dc3545',
    confirmButtonText: 'Yes',
    showLoaderOnConfirm: true,
    preConfirm: async () => {
      try {
        const response = await fetch('../api/posts/posts.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            action: 'rejectPost',
            post_id: postId,
          }),
        });

        const result = await response.json();

        if (!response.ok) {
          throw new Error(result.message || 'Rejecting post failed');
        }

        if (result.status !== 'success') {
          Swal.fire('', result.message, result.status);
          return;
        }

        Swal.fire('Success', result.message, result.status).then(() => {
          fetchPosts();
        });
      } catch (error) {
        console.error(error);
        Swal.fire('', error.message, 'error');
      }
    },
  });
});
