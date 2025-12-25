'use strict';

const MAX_FILE_SIZE = 8 * 1024 * 1024;
const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/jpg', 'image/png'];

const postForm = document.getElementById('post-form');
const formType = postForm.dataset.formType;
const postId = postForm.dataset.postId;
const submitBtn = document.getElementById('submit-btn');
let imageInput = document.getElementById('image');
const imagePreview = document.getElementById('image-preview');

imageInput.addEventListener('change', function (e) {
  const [file] = e.target.files;

  if (!file) {
    resetImagePreview();
    return;
  }

  if (!ALLOWED_MIME_TYPES.includes(file.type)) {
    imageInput.classList.add('is-invalid');
    imageInput.classList.remove('is-valid');
    imageInput.nextElementSibling.textContent =
      'Only JPG/JPEG and PNG are allowed';
    imageInput.nextElementSibling.classList.add('d-block');
    imageInput.value = '';
    resetImagePreview();
    return;
  }

  const reader = new FileReader();

  reader.onload = (e) => {
    imagePreview.src = e.target.result;
    imagePreview.classList.remove('d-none');
  };

  reader.readAsDataURL(file);
});

function resetImagePreview() {
  imagePreview.src = '';
  imagePreview.classList.add('d-none');
}

// create post
postForm.addEventListener('submit', async function (e) {
  e.preventDefault();

  const action = formType === 'create-post-form' ? 'createPost' : 'updatePost';

  const title = document.getElementById('title').value.trim();
  const category = document.getElementById('category').value;
  const imageInput = document.getElementById('image');
  const imageFile = imageInput?.files?.[0];
  const content = document.getElementById('content').value.trim();

  let isValid = true;

  validateField('title', title === '', 'Title is required');
  validateField(
    'category',
    category === null || category === '',
    'Category is required'
  );

  if (action === 'createPost') {
    validateField('image', !imageFile, 'Image is required');
  }
  // validate file size
  validateField('content', content === '', 'Content is required');

  if (!isValid) {
    submitBtn.disabled = false;
    return;
  }

  const formData = new FormData();
  formData.append('post_id', postId);
  formData.append('title', title);
  formData.append('category', category);
  formData.append('image', imageFile);
  formData.append('content', content);
  formData.append('action', action);

  try {
    const response = await fetch('../api/posts/create_update_post.php', {
      method: 'POST',
      body: formData,
    });

    const result = await response.json();

    if (!response.ok) {
      throw new Error(result.message || 'Submit failed');
    }

    if (result.status !== 'success') {
      Toast.fire({ icon: result.status, title: result.message }).then(() => {
        createBtn.disabled = false;
      });
      return;
    }

    if (action === 'createPost') {
      Swal.fire('Success', result.message || 'Creating post failed', 'success').then(
        () => {
          postForm.reset();
          resetField('title');
          resetField('category');
          resetField('image');
          resetField('content');
          resetImagePreview();
        }
      );
    }

    if (action === 'updatePost') {
      Swal.fire('Success', result.message || 'Updating post failed', 'success').then(
        () => {
          postForm.reset();
          resetField('title');
          resetField('category');
          resetField('image');
          resetField('content');
        }
      );
    }
  } catch (error) {
    console.error(error);
    Swal.fire('Failed', 'Network error. Please try again.', 'error');
  } finally {
    submitBtn.disabled = false;
  }

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

  function resetField(fieldId) {
    const field = document.getElementById(fieldId);
    const feedBackElement = field.nextElementSibling;

    field.classList.remove('is-valid');
    field.classList.remove('is-invalid');
    feedBackElement.classList.remove('d-block');
  }
});

// update post
