$(document).ready(function () {
  let currentPageInMemory = getPageFromUrl();
  const status = $('#donation-container').data('status');

  function getPageFromUrl() {
    const params = new URLSearchParams(window.location.search);
    const p = params.get('page');
    return p && !isNaN(p) && parseInt(p) > 0 ? parseInt(p) : 1;
  }

  function loadPage(status, page) {
    page = parseInt(page) || 1;

    $('#donation-container').html(`
      <div class="text-center">
        <div class="spinner-border text-warning" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </div>
    `);

    $.get('../../api/donations/donations_data.php', {
      page: page,
      status: status,
    })
      .done(function (html) {
        $('#donation-container').html(html);

        if (page !== currentPageInMemory) {
          currentPageInMemory = page;

          const newUrl = `?status=${status}&page=${page}`;
          history.pushState({ page: page }, '', newUrl);
        }

        /* if (page !== currentPageInMemory) {
          currentPageInMemory = page;

          const newUrl =
            page === 1
              ? window.location.pathname
              : `?status=${status}&page=${page}`;
          history.pushState({ page: page }, '', newUrl);
        } */
      })
      .fail(function () {
        $('#donation-container').html(
          '<div class="alert alert-danger" role="alert">Failed to load data.</div>'
        );
      });
  }

  loadPage(status, currentPageInMemory);

  $(document).on('click', '.ajax-page', function (e) {
    e.preventDefault();
    const $li = $(this).parent();
    if ($li.hasClass('disabled') || $li.hasClass('active')) return;
    const page = parseInt($(this).data('page'));
    loadPage(status, page);
    $('html, body').animate({ scrollTop: 0 }, 300);
  });

  window.addEventListener('popstate', function (event) {
    const page = event.state?.page || getPageFromUrl();
    currentPageInMemory = page;
    loadPage(status, page);
  });
});
