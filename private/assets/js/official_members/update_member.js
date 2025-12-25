'use strict';

const updateForm = document.getElementById('update-member-form');

updateForm.addEventListener('submit', async function (e) {
  e.preventDefault();

  Swal.fire('Success', 'Member updated successfully', 'success').then(() => {
    window.location.reload();
  });
});
