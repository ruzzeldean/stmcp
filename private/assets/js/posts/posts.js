'use strict';

// store current page (localstorage)

const tableBody = document.getElementById('table-body');
const userName = tableBody.dataset.userName;
const userRole = tableBody.dataset.userRole;

let currentPost = [];

const getPaginationRange = (current, last) => {
  const delta = 1;
  const left = current - delta;
  const right = current + delta + 1;
  const range = [];
  const rangeWithDots = [];
  let l;

  for (let i = 1; i <= last; i++) {
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

async function loadTableData(event, page = 1) {
  if (event && typeof event.preventDefault === 'function') {
    event.preventDefault();
  }

  try {
    const response = await fetch(`../api/posts/posts.php?page=${page}`, {
      method: 'GET',
      headers: { Accept: 'application/json' },
    });

    const result = await response.json();

    if (!response.ok) {
      throw new Error(result.message || 'Error fetching posts');
    }

    currentPost = result.data.posts;
    renderTable(result.data.posts);
    renderPagination(result.data.total_pages, result.data.current_page);
  } catch (error) {
    console.error('Error fetching posts:', error);
  }
}

function renderTable(posts) {
  tableBody.innerHTML = posts
    .map((post) => {
      const categoryConfig = {
        Announcement: { class: 'text-bg-warning', label: 'Announcement' },
        Upcoming: { class: 'text-bg-info', label: 'Upcoming' },
        'Past Event': { class: 'text-bg-success', label: 'Past Event' },
      };

      const category = categoryConfig[post.category] || {
        class: 'text-bg-secondary',
        label: post.category,
      };

      const statusConfig = {
        Pending: { class: 'text-bg-secondary', label: 'Pending' },
        Published: { class: 'text-bg-success', label: 'Published' },
        Rejected: { class: 'text-bg-danger', label: 'Rejected' },
      };

      const status = statusConfig[post.status] || {
        class: 'text-bg-info',
        label: post.status,
      };

      const display = userRole === 'moderator' ? 'd-none' : '';
      const editBtn = userName !== post.created_by ? 'd-none' : '';

      return `
        <tr>
          <td>${post.title}</td>
          <td>
            <span class="badge rounded-pill ${category.class}">
              ${category.label}
            </span>
          </td>
          <td>
            <img class="img-thumbnail max-w-50"
            src="../storage/uploads/posts/${post.image_path}"
            alt="${post.title}" />
          </td>
          <td>
            <span class="badge rounded-pill ${status.class}">
              ${status.label}
            </span>
          </td>
          <td>${post.created_by}</td>
          <td>${post.created_at}</td>
          <td>
            <button class="preview-btn btn btn-primary" type="button"
            data-bs-toggle="modal" data-bs-target="#preview-modal"
            data-post-id="${post.post_id}" title="Preview">
              <i class="fa-solid fa-eye"></i>
            </button>
            
            <button class="approve-btn btn btn-success ${display}"
            data-post-id="${post.post_id}" title="Approve">
              <i class="fa-solid fa-check"></i>
            </button>

            <button class="reject-btn btn btn-danger ${display}"
            data-post-id="${post.post_id}" title="Reject">
              <i class="fa-solid fa-xmark"></i>
            </button>

            <a class="edit-btn btn btn-info ${editBtn}"
            href="update_post.php?id=${post.post_id}"
            data-post-id="${post.post_id}" title="Edit">
              <i class="fa-solid fa-pen-to-square"></i>
            </a>
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

  html += `
    <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
      <a class="page-link" href="#" onclick="loadTableData(event, ${
        currentPage - 1
      })">&lsaquo;</a>
    </li>
  `;

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

  html += `
    <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
      <a class="page-link" href="#" onclick="loadTableData(event, ${
        currentPage + 1
      })">&rsaquo;</a>
    </li>
  `;

  nav.innerHTML = html;
}

document.addEventListener('DOMContentLoaded', () => loadTableData(null, 1));

// preview btn
document.addEventListener('click', function (event) {
  const previewBtn = event.target.closest('.preview-btn');
  if (!previewBtn) return;

  const postId = previewBtn.dataset.postId;

  const post = currentPost.find((p) => p.post_id == postId);
  const categoryConfig = {
    Announcement: { class: 'text-bg-warning', label: 'Announcement' },
    Upcoming: { class: 'text-bg-info', label: 'Upcoming' },
    'Past Event': { class: 'text-bg-success', label: 'Past Event' },
  };

  const category = categoryConfig[post.category] || {
    class: 'text-bg-secondary',
    label: post.category,
  };

  if (post) {
    const modalBody = document.querySelector('.modal-body');
    modalBody.innerHTML = `
      <h3 id="post-title" class="mb-1">${post.title}</h3>

      <div>
        <span id="post-category"
        class="badge rounded-pill ${category.class}">
          ${category.label}
        </span> |
        <small id="post-date" class="badge text-muted">${post.created_at}</small>
      </div>

      <img id="post-image" class="img-fluid rounded my-3"
      src="../storage/uploads/posts/${post.image_path}" alt="${post.title}">

      <div class="ws-pre-wrap">${post.content}</div>
    `;
  }
});

// aprove btn
document.addEventListener('click', function (event) {
  const approveBtn = event.target.closest('.approve-btn');

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

        Swal.fire('Success', result.message, result.status).then(() => {
          loadTableData(null, 1);
        });
      } catch (error) {
        console.error(error);
        Swal.fire('', error.message, 'error');
      }
    },
  });
});

// reject btn
document.addEventListener('click', function (event) {
  const rejectBtn = event.target.closest('.reject-btn');
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
          loadTableData(null, 1);
        });
      } catch (error) {
        console.error(error);
        Swal.fire('', error.message, 'error');
      }
    },
  });
});
