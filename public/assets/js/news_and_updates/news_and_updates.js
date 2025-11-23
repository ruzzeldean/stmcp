$(document).ready(function () {
  const savedPage = sessionStorage.getItem('news_updates_page') || 1;
  loadPosts(savedPage);

  $(document).on('click', '.pagination-link', function (e) {
    e.preventDefault();

    const page = $(this).data('page');
    sessionStorage.setItem('news_updates_page', page);
    loadPosts(page);
  });
});

function loadPosts(page = 1) {
  $('#loading-spinner').removeClass('d-none');

  $.ajax({
    url: './actions/news_and_updates/fetch_all_news.php',
    method: 'GET',
    data: { page },
    dataType: 'json',
    success: (res) => {
      const container = $('#posts-container');
      container.empty();

      if (res.status === 'success' && res.data.posts.length > 0) {
        const posts = res.data.posts;
        const { currentPage, totalPages } = res.data.pagination;

        $.each(posts, function (_, post) {
          const dateObject = new Date(post.created_at);
          const created_at = formatDate(dateObject);

          const card = `
              <div class="col-lg-6">
                <div class="card h-100 overflow-hidden">
                  <div class="row g-0">
                    <div class="col-lg-6">
                      <img class="news-updates-img img-fluid h-100" src="/stmcp/uploads/posts/${post.image_path}">
                    </div>

                    <div class="col-lg-6">
                      <div class="card-body p-lg-4 d-flex flex-column gap-5">
                        <div>
                          <h5 class="card-title custom-text-truncate-1">${post.title}</h5>
                          <p class="card-subtitle text-muted mb-3"><small>${created_at}</small></p>
                          <p class="card-text custom-text-truncate-3">${post.content}</p>
                        </div>
                        
                        <div>
                          <a class="btn btn-warning stretched-link" href="./view.php?id=${post.post_id}">Read More</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            `;

          container.append(card);
        });

        renderPagination(currentPage, totalPages);
      } else {
        container.html(
          '<p class="lead text-muted text-center">No posts available.</p>'
        );

        $('#pagination-wrapper').empty();
      }
    },
    error: (err) => {
      console.error(err);

      $('#posts-container').html(`
        <div class="alert alert-warning text-center">
          Failed to load posts. Please try again later.
        </div>
      `);
    },
    complete: () => {
      $('#loading-spinner').addClass('d-none');
    },
  });
}

function renderPagination(current, total) {
  const pagination = $('#pagination-wrapper');
  pagination.empty();

  if (total <= 1) return;

  let html = `<ul class="pagination justify-content-center">`;

  html += `
    <li class="page-item ${current === 1 ? 'disabled' : ''}">
      <a href="#" class="page-link pagination-link" data-page="${
        current - 1
      }">Previous</a>
    </li>
  `;

  for (let i = 1; i <= total; i++) {
    html += `
      <li class="page-item ${i === current ? 'active' : ''}">
        <a href="#" class="page-link pagination-link" data-page="${i}">${i}</a>
      </li>
    `;
  }

  html += `
    <li class="page-item ${current === total ? 'disabled' : ''}">
      <a href="#" class="page-link pagination-link" data-page="${
        current + 1
      }">Next</a>
    </li>
  `;

  html += `</ul>`;
  pagination.html(html);
}

function formatDate(date) {
  const options = {
    weekday: 'short',
    day: '2-digit',
    month: 'short',
    year: 'numeric',
  };

  const formatter = new Intl.DateTimeFormat('en-US', options);
  const parts = formatter.formatToParts(date);

  const weekday = parts.find((p) => p.type === 'weekday').value;
  const day = parts.find((p) => p.type === 'day').value;
  const month = parts.find((p) => p.type === 'month').value;
  const year = parts.find((p) => p.type === 'year').value;

  return `${weekday}, ${day} ${month} ${year}`;
}
