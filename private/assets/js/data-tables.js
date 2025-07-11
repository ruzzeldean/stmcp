$('.data-table').DataTable({
  layout: {
    topStart: {
      buttons: [
        {
          extend: 'excel',
        },
        {
          extend: 'colvis',
          columns: ':not(:first-child)',
        },
      ],
    },
  },
  columnDefs: [
    {
      className: 'dtr-control',
      orderable: false,
      target: 0,
      visible: true,
    },
  ],
  order: [1, 'asc'],
  responsive: {
    details: {
      type: 'column',
      target: 'tr',
    },
  },
});
