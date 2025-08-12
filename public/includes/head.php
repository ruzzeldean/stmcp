<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Star Touring Motorcycle Club Philippines</title>
<link rel="shortcut icon" href="./assets/img/logo/logo.png" type="image/x-icon">

<?php if ($currentPage === 'about.php'): ?>
  <!-- Font Awesome Icon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<?php endif; ?>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

<?php if ($currentPage === 'join_us.php'): ?>
  <!-- iziToast -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
<?php endif; ?>

<!-- Custom CSS -->
<link rel="stylesheet" href="./assets/css/style.css">