'use strict';

const Toast = Swal.mixin({
  toast: true,
  position: 'center',
  showConfirmButton: false,
  timer: 1500,
  timerProgressBar: true,
});

const loginForm = document.getElementById('login-form');
const loginBtn = document.getElementById('login-btn');

loginForm.addEventListener('submit', async function (e) {
  e.preventDefault();
  loginBtn.disabled = true;

  const username = document.getElementById('username').value.trim();
  const password = document.getElementById('password').value;

  let valid = true;

  if (!username) {
    Toast.fire({ icon: 'warning', title: 'Username is required' });
    valid = false;
    loginBtn.disabled = false;
    return;
  }

  if (!password) {
    Toast.fire({ icon: 'warning', title: 'Password is required' });
    valid = false;
    loginBtn.disabled = false;
    return;
  }

  if (!valid) {
    loginBtn.disabled = false;
  }

  const payload = {
    username: username,
    password: password,
  };

  try {
    const response = await fetch('../api/login.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(payload),
    });

    const result = await response.json();

    if (!response.ok) {
      throw new Error(result.message || 'Login failed');
    }

    if (response.ok) {
      Toast.fire({ icon: 'success', title: result.message }).then(() => {
        window.location.href = result.redirect;
      });
    }
  } catch (error) {
    // console.log(error);
    Toast.fire({ icon: 'error', title: error.message || 'Network error' });
    loginBtn.disabled = false;
  }
});
