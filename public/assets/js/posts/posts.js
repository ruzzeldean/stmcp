$(function () {
  const savedPage = sessionStorage.getItem('past_page') || 1;
  loadPastPosts(savedPage);

  $(document).on('click', '.pagination-link', function (e) {
    e.preventDefault();

    const page = $(this).data('page');
    sessionStorage.setItem('past_page', page);
    loadPastPosts(page);
  });

  function loadPastPosts(page) {
    $('#loading-spinner').removeClass('d-none');

    $.ajax({
      url: './actions/posts/load_upcoming.php',
      method: 'GET',
      data: { page },
      success: (response) => {
        $('#past-posts-wrapper').html(response);
      },
      error: () => {
        $('#past-posts-wrapper').html(`
          <div class="alert alert-warning text-center">
            Failed to load posts. Please check your internet connection.
          </div>
        `);
      },
      complete: () => {
        $('#loading-spinner').addClass('d-none');
      },
    });
  }
});
