$(function () {
  const originalImage = $('#image-preview').attr('src');

  $('#image').on('change', function () {
    const file = this.files[0];

    if (file) {
      const fileType = file.type;
      const validType = ['image/jpeg', 'image/png'];

      if (!validType.includes(fileType)) {
        alert('Only JPG and PNG images are allowed');
        $('#image').val('');
        $('#image-preview').attr('src', originalImage).removeClass('d-none');
        return;
      }

      const reader = new FileReader();
      reader.onload = function (e) {
        $('#image-preview').attr('src', e.target.result).removeClass('d-none');
      };
      reader.readAsDataURL(file);
    } else {
      $('#image-preview').attr('src', originalImage).removeClass('d-none');
    }
  });
});
