$(function () {
  // post status filter
  const table = $('.data-table').DataTable();

  $('#post-status').on('change', function () {
    const selected = $(this).val();

    if (selected === '') {
      table.column(5).search('').draw();
    } else {
      table
        .column(5)
        .search('^' + selected + '$', true, false)
        .draw();
    }
  });

  $('#post-status').trigger('change');
});
