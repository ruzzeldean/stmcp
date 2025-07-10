$(function () {
  const MAX_TITLE_LENGTH = 100;
  const MAX_CONTENT_LENGTH = 5000;
  const MAX_FILE_SIZE = 5 * 1024 * 1024;
  const ALLOWED_TYPES = ['image/jpeg', 'image/png'];

  $('#image').on('change', function () {
    const file = this.files[0];

    if (file) {
      const fileType = file.type;
      const validTypes = ['image/jpeg', 'image/png'];

      if (!validTypes.includes(fileType)) {
        alert('Only JPG and PNG images are allowed');
        $('#image').val('');
        $('#image-preview').addClass('d-none').attr('src', '');
        return;
      }

      const reader = new FileReader();
      reader.onload = function (e) {
        $('#image-preview').attr('src', e.target.result).removeClass('d-none');
      };
      reader.readAsDataURL(file);
    } else {
      $('#image-preview').addClass('d-none').attr('src', '');
    }
  });

  $('#create-btn').click(function () {
    const title = $('#title').val().trim();
    const category = $('#category').val();
    const image = $('#image')[0].files[0];
    const content = $('#content').val().trim();
    const csrfToken = $(this).data('csrfToken');

    if (title === '') {
      alert('Title is required');
    } else if (title.length > MAX_TITLE_LENGTH) {
      alert(`Title must be less than ${MAX_TITLE_LENGTH} characters`);
    } else if (category === null) {
      alert('Category is required');
    } else if (!image) {
      alert('Image is required');
    } else if (!ALLOWED_TYPES.includes(image.type)) {
      alert('Only JPG and PNG images are allowed');
    } else if (image.size > MAX_FILE_SIZE) {
      alert('Image is too large. Max is 5MB');
    } else if (content === '') {
      alert('Content is required');
    } else if (content.length > MAX_CONTENT_LENGTH) {
      alert(`Content must be less than ${MAX_CONTENT_LENGTH} characters`);
    } else {
      const formData = new FormData();
      formData.append('title', title);
      formData.append('category', category);
      formData.append('image', image);
      formData.append('content', content);
      formData.append('csrfToken', csrfToken);

      $('#create-btn').prop('disabled', true);

      $.ajax({
        url: './actions/process_create_post.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: (response) => {
          if (response.status === 'success') {
            alert(response.message);
            location.reload();
          } else {
            alert(response.message);
          }
          $('#create-btn').prop('disabled', false);
        },
        error: () => {
          alert('Something went wrong');
          $('#create-btn').prop('disabled', false);
        },
      });
    }
  });
});
