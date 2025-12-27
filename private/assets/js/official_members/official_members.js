'use strict';

document.addEventListener('DOMContentLoaded', function () {
  const $table = $('.data-table');

  $table.DataTable({
    layout: {
      topStart: {
        buttons: [
          {
            extend: 'excel',
          },
          {
            extend: 'colvis',
            columns: ':gt(1)',
            //columns: ':not(:first-child):not(:last-child)',
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
    responsive: {
      details: {
        type: 'column',
        target: 'tr',
      },
    },

    ajax: {
      url: '../api/official_members/official_members.php',
      dataSrc: 'data',
    },

    columns: [
      {
        data: null,
        defaultContent: '',
        orderable: false,
      },
      {
        data: null,
        defaultContent: '',
        orderable: false,
        render: function (data) {
          return `
            <a class="btn btn-primary"
            href="update_member.php?id=${data.person_id}" title="Update">
              <i class="fa-solid fa-user-pen"></i>
            </a>
            
            <button class="delete-btn btn btn-danger"
            data-person-id="${data.person_id}" title="Delete">
              <i class="fa-solid fa-user-xmark"></i>
            </button>
          `;
        },
      },
      { data: 'first_name' },
      { data: 'middle_name' },
      { data: 'last_name' },
      { data: 'chapter_name' },
      { data: 'date_of_birth' },
      { data: 'civil_status' },
      { data: 'blood_type' },
      { data: 'home_address' },
      { data: 'phone_number' },
      { data: 'email' },
      { data: 'emergency_contact_name' },
      { data: 'emergency_contact_number' },
      { data: 'occupation' },
      { data: 'license_number' },
      { data: 'motorcycle_brand' },
      { data: 'motorcycle_model' },
      { data: 'sponsor' },
      { data: 'other_club_affiliation' },
      { data: 'date_joined' },
      { data: 'created_at' },
      { data: 'updated_at' },
    ],
  });
});

$(document).on('click', '.delete-btn', function () {
  const personId = $(this).data('person-id');

  Swal.fire({
    text: 'Are you sure you want to delete this record?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc3545',
    confirmButtonText: 'Yes',
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: 'Confirm deletion',
        input: 'password',
        inputPlaceholder: 'Enter your password',
        showLoaderOnConfirm: true,

        preConfirm: async (password) => {
          if (!password) {
            Swal.showValidationMessage('Password is required');
            return;
          }

          try {
            const response = await fetch(
              '../api/official_members/official_members.php',
              {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                  action: 'deleteMember',
                  person_id: personId,
                  password,
                }),
              }
            );

            const result = await response.json();

            if (!response.ok) {
              Swal.showValidationMessage(
                result.message || 'Incorrect password'
              );
              return;
            }

            return result;
          } catch (error) {
            Swal.showValidationMessage(`
              Request failed: ${error}
            `);
          }
        },
        allowOutsideClick: () => !Swal.isLoading(),
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire('Deleted!', 'Member has been removed.', 'success').then(
            () => {
              $('.data-table').DataTable().ajax.reload(null, false);
            }
          );
        }
      });
    }
  });
});

const truncateRenderer = function (data, type) {
  if (type !== 'display') {
    return data;
  }

  return `<span class="text-truncate d-inline-block">${data}</span>`;
};
