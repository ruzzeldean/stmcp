<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>
</nav>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="./" class="brand-link">
    <img src="../../assets/shared/images/logo/logo.png" alt="STMCP Logo" class="brand-image">
    <span class="brand-text font-weight-light">MODERATOR | STMCP</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="../../assets/shared/images/user-icon/user-icon.png" alt="User Icon">
      </div>
      <div class="info">
        <a href="#" class="d-block"><?= e(strtoupper($_SESSION['first_name'])); ?></a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="./" class="nav-link <?= e($currentPage === 'index.php' ? 'active' : '') ?>">
            <i class="nav-icon fa-solid fa-gauge-high"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="./aspirants.php" class="nav-link <?= e($currentPage === 'aspirants.php' ? 'active' : '') ?>">
            <i class="nav-icon fa-solid fa-user-check"></i>
            <p>
              Aspirants
            </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="./contacts.php" class="nav-link <?= e($currentPage === 'messages.php' || $currentPage === 'contacts.php' ? 'active' : '') ?>">
          <i class="nav-icon fa-solid fa-message"></i>
            <p>Messages</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="./posts.php" class="nav-link <?= e($currentPage === 'posts.php' || $currentPage === 'create_post.php' || $currentPage === 'edit_post.php' ? 'active' : '') ?>">
            <i class="nav-icon fa-solid fa-newspaper"></i>
            <p>Posts</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="./donations.php" class="nav-link <?= e($currentPage === 'donations.php' ? 'active' : '') ?>">
            <i class="nav-icon fa-solid fa-coins"></i>
            <p>Donations</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="./merchandise.php" class="nav-link <?= e($currentPage === 'merchandise.php' ? 'active' : '') ?>">
            <i class="nav-icon fa-solid fa-tags"></i>
            <p>Merchandise</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="./settings.php" class="nav-link <?= e($currentPage === 'settings.php' ? 'active' : '') ?>">
            <i class="nav-icon fa-solid fa-gear"></i>
            <p>Settings</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="../../" class="nav-link">
            <i class="nav-icon fa-solid fa-arrow-right-from-bracket"></i>
            <p>Logout</p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>