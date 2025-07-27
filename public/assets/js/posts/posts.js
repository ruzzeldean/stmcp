$(function () {
  const savedPage = sessionStorage.getItem('upcoming_page') || 1;
  loadUpcomingPosts(savedPage);

  $(document).on('click', '.pagination-link', function (e) {
    e.preventDefault();

    const page = $(this).data('page');
    sessionStorage.setItem('upcoming_page', page);
    loadUpcomingPosts(page);
  });

  function loadUpcomingPosts(page) {
    $('#loading-spinner').removeClass('d-none');

    $.ajax({
      url: './actions/posts/load_upcoming.php',
      method: 'GET',
      data: { page },
      success: (response) => {
        $('#upcoming-posts-wrapper').html(response);
      },
      error: () => {
        $('#upcoming-posts-wrapper').html(`
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
