<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>

<!-- jQuery -->
<script src="../../assets/shared/js/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../assets/shared/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../assets/shared/js/adminlte.min.js"></script>
<?php if ($currentPage === 'official_members.php' || $currentPage === 'edit_member.php' || $currentPage === 'posts_management.php'): ?>
  <?php require_once __DIR__ . '/../../includes/datatables/scripts_include.php'; ?>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Custom Script -->
  <script src="../../assets/shared/js/data_tables.js"></script>
  <script src="../../assets/shared/js/shared.js"></script>
<?php endif; ?>