<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin | Star Touring Motorcycle Club</title>
<link rel="shortcut icon" href="../../assets/shared/images/logo/logo.png" type="image/x-icon">
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet"
  href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- AdminLTE 4 -->
<link rel="stylesheet" href="../../assets/shared/css/adminlte.min.css">
<?php if ($currentPage === 'official_members.php' || $currentPage === 'posts_management.php'): ?>
  <!-- DataTables -->
  <?php require_once __DIR__ . '/../../includes/datatables/styles_include.php'; ?>
<?php endif; ?>
<link rel="stylesheet" href="../../assets/shared/css/style.css">