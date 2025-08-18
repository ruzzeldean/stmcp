<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Moderator | Star Touring Motorcycle Club</title>
<link rel="shortcut icon" href="../../assets/shared/images/logo/logo.png" type="image/x-icon">
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet"
  href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome Icon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- AdminLTE 4 -->
<link rel="stylesheet" href="../../assets/shared/css/adminlte.min.css">
<?php
if ($currentPage === 'aspirants.php' || $currentPage === 'posts.php') {
  require_once __DIR__ . '/../../includes/datatables/styles_include.php';
}
?>
<link rel="stylesheet" href="../../assets/shared/css/style.css">