'use strict';

const postsContainer = document.getElementById('posts-container');
const paginationControl = document.getElementById('pagination-controls');
const paginationInfo = document.getElementById('pagination-info');

async function fetchPosts(type, page = 1) {
  try {
    const response = await fetch(
      `../api/backend.php?type=${encodeURIComponent(
        type
      )}&page=${encodeURIComponent(page)}`,
      {
        method: 'GET',
        headers: {
          Accept: 'application/json',
        },
      }
    );

    const result = await response.json();

    if (!response.ok) {
      throw new Error(result.message || 'Fetching posts failed');
    }

    if (result.status !== 'success') {
      alert(result.message || 'Failed to load posts');
      return;
    }

    renderPosts(result.data.news_updates);
    renderPagination(
      result.data.pagination.total_pages,
      result.data.pagination.current_page
    );
    renderPaginationInfo(result.data.pagination);
  } catch (error) {
    console.error(error);
    if (postsContainer) {
      postsContainer.innerHTML = `
        <div class="alert alert-warning text-center" role="alert">
          Unable to load posts.
        </div>
      `;
    }
  }
}

function renderPosts(posts) {
  if (!postsContainer) return;
  postsContainer.innerHTML = '';

  if (!Array.isArray(posts) || posts.length === 0) {
    postsContainer.innerHTML = `
      <div class="col-12 text-center text-muted">
        <p class="lead">No posts available.</p>
      </div>
    `;
    return;
  }

  posts.forEach((post) => {
    const postCard = createPostCard(post);
    postsContainer.appendChild(postCard);
  });
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

    fetchPosts('news_updates', page);
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

function createPostCard(post) {
  const col = document.createElement('div');
  col.className = 'col-md-6';

  const safeEscape = typeof escapeHtml === 'function';

  const imagePath = post?.image_path
    ? encodeURIComponent(post.image_path).replace(/%2F/g, '/')
    : 'uploads/posts/default.png';
  const title = safeEscape ? escapeHtml(post?.title ?? '') : post?.title ?? '';
  const content = safeEscape
    ? escapeHtml(post?.content ?? '')
    : post?.content ?? '';
  const createdAt = safeEscape
    ? escapeHtml(post?.created_at ?? '')
    : post?.created_at ?? '';
  const category = safeEscape
    ? escapeHtml(post?.category ?? '')
    : post?.category ?? '';

  const postId = post?.post_id ? encodeURIComponent(post.post_id) : '';

  /* col.innerHTML = `
    <div class="card shadow-sm h-100">
      <img src="../assets/${imagePath}" class="acts-img card-img-top"
      loading="lazy" alt="${title}">

      <div class="card-body d-flex flex-column">
        <h5 class="card-title mb-2">${title}</h5>

        <small class="card-subtitle text-muted">
        ${createdAt} |
        <span class="badge rounded-pill text-bg-${getCategory(post?.category)}">
        ${category}</span></small>

        <p class="card-text mt-3 ws-pre-wrap custom-text-truncate-3">${content}</p>
      </div>

      <div class="card-footer bg-transparent border-0 pb-3">
        <a class="btn btn-warning" href="view.php?id=${postId}">Read More</a>
      </div>
    </div>
  `; */

  col.innerHTML = `
    <div class="card shadow-sm h-100 overflow-hidden">
      <div class="row g-0 h-100">
        <div class="col-md-4">
          <img src="../assets/${imagePath}" class="img-fluid news-updates-img" alt="${title}">
        </div>
        <div class="col-md-8 d-flex flex-column">

          <div class="card-body d-flex flex-column">
            <h5 class="card-title mb-2 text-truncate">${title}</h5>

            <small class="card-subtitle text-muted">
            ${createdAt} |
            <span class="badge rounded-pill text-bg-${getCategory(
              post?.category
            )}">
            ${category}</span></small>

            <p class="card-text mt-3 ws-pre-wrap custom-text-truncate-3">${content}</p>
          </div>
          
          <div class="card-footer bg-transparent border-0">
            <a class="btn btn-warning" href="view.php?id=${postId}">Read More</a>
          </div>
        </div>
      </div>
    </div>
  `;

  return col;
}

function getCategory(category) {
  const categoryMap = {
    Upcoming: 'primary',
    'Past Event': 'success',
  };

  return categoryMap[category] ?? 'info';
}

document.addEventListener('DOMContentLoaded', () => fetchPosts('news_updates'));
