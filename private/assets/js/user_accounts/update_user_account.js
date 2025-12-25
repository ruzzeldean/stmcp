'use strict';

const updateForm = document.getElementById('update-form');
const updateBtn = document.getElementById('update-btn');

updateForm.addEventListener('submit', async function (e) {
  e.preventDefault();

  let isValid = true;

  if (updateBtn) {
    updateBtn.disabled = true;
  }

  const userId = document.getElementById('user-id').value;
  const username = document.getElementById('username').value.trim();
  const newPassword = document.getElementById('new-password').value.trim();
  const confirmPassword = document
    .getElementById('confirm-password')
    .value.trim();
  const roleId = document.getElementById('role-id').value;
  const status = document.getElementById('status').value;

  validateField('username', username === '', 'Username is required');

  if (newPassword !== '' || confirmPassword !== '') {
    const passwordsMatch = newPassword === confirmPassword;
    validateField('new-password', !passwordsMatch, 'Passwords do not match');
    validateField(
      'confirm-password',
      !passwordsMatch,
      'Passwords do not match'
    );
  }

  validateField('role-id', !roleId || roleId === null, 'Role is required');
  validateField('status', !status || status === null, 'Status is required');

  if (!isValid) {
    Toast.fire({
      icon: 'warning',
      title: 'Please complete all required fields correctly.',
    });

    if (updateBtn) {
      updateBtn.disabled = false;
    }

    return;
  }

  const formData = {
    user_id: parseInt(userId),
    username: username,
    ...(newPassword && { new_password: newPassword }),
    role_id: parseInt(roleId),
    status: status,
    action: 'updateUserAccount',
  };

  updateUserAccount(formData);

  // helper function
  function validateField(fieldId, condition, errorMessage) {
    const field = document.getElementById(fieldId);
    const feedBackElement = field.nextElementSibling;

    if (condition) {
      field.classList.add('is-invalid');
      field.classList.remove('is-valid');
      feedBackElement.textContent = errorMessage;
      feedBackElement.classList.add('d-block');
      isValid = false;
    } else {
      field.classList.remove('is-invalid');
      field.classList.add('is-valid');
      feedBackElement.classList.remove('d-block');
    }
  }
});

async function updateUserAccount(formData) {
  try {
    const response = await fetch('../api/user_accounts/user_accounts.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(formData),
    });

    const result = await response.json();

    if (!response.ok) {
      throw new Error(result.message || 'Update failed');
    }

    if (result.status !== 'success') {
      Toast.fire({ icon: result.status, title: result.message }).then(() => {
        updateBtn.disabled = false;
      });
      return;
    }

    Swal.fire(
      'Success',
      result.message || 'Updated successful',
      'success'
    ).then(() => {
      window.location.reload();
    });
  } catch (error) {
    console.error(error);
    Toast.fire({ icon: 'error', title: 'Network error' });

    updateBtn.disabled = false;
  }
}
