$(document).ready(function () {
  const savedPage = sessionStorage.getItem('activities_page') || 1;
  loadPosts(savedPage);

  $(document).on('click', '.pagination-link', function (e) {
    e.preventDefault();

    const page = $(this).data('page');
    sessionStorage.setItem('activities_page', page);
    loadPosts(page);
  });
});

function loadPosts(page = 1) {
  $('#loading-spinner').removeClass('d-none');

  $.ajax({
    url: './actions/posts/fetch_all_posts.php',
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
          const card = `
              <div class="col-md-6 col-lg-4">
                <div class="card shadow overflow-hidden">
                  <img class="bg-secondary card-img-top past-act-img" src="/stmcp/uploads/posts/${
                    post.image_path
                  }" alt="${post.title}">

                  <div class="card-body">
                    <h5 class="card-title custom-text-truncate-1 mb-0 pb-1" title="${
                      post.title
                    }">${post.title}</h5>

                    <p class="card-text"><small class="text-muted">${new Date(
                      post.created_at
                    ).toLocaleDateString()}</small> | <span class="badge text-bg-success">${
            post.category
          }</span></p>
                  </div>

                  <div class="card-footer bg-dark border-0 py-3">
                    <a class="btn btn-warning" href="./view.php?id=${
                      post.post_id
                    }">Read more</a>
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
