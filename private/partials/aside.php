    <aside class="app-sidebar bg-dark shadow" data-bs-theme="dark">
      <div class="sidebar-brand text-start">
        <a href="./dashboard.php" class="brand-link">
          <!-- Brand Logo -->
          <img
            src="../assets/images/logo/stmcp_official_back_logo.png"
            alt="STMCP Logo"
            class="brand-image opacity-75 shadow rounded-circle" />
          <!-- Brand Name -->
          <span class="brand-text fw-light">STMCP</span>
        </a>
      </div>

      <div class="sidebar-wrapper">
        <nav class="mt-2">
          <ul
            class="nav sidebar-menu flex-column"
            data-lte-toggle="treeview"
            role="navigation"
            aria-label="Main navigation"
            data-accordion="false"
            id="navigation">

            <!-- <li class="nav-header">HOME</li> -->

            <!-- Nav links -->
            <li class="nav-item">
              <a href="./dashboard.php" class="nav-link <?= $currentPage === 'index.php' || $currentPage === 'dashboard.php' ? 'active' : '' ?>">
                <i class="nav-icon fa-solid fa-gauge"></i>
                <p>Dashboard</p>
              </a>
            </li>

            <?php if ($superAdmin || $admin) : ?>
            <li class="nav-item">
              <a href="./official_members.php" class="nav-link <?= $currentPage === 'official_members.php' ? 'active' : '' ?>">
                <i class="nav-icon fa-solid fa-user-check"></i>
                <p>Official Members</p>
              </a>
            </li>
            <?php endif; ?>

            <?php if ($superAdmin || $moderator) : ?>
            <li class="nav-item">
              <a href="./aspirants.php" class="nav-link <?= $currentPage === 'aspirants.php' ? 'active' : '' ?>">
                <i class="nav-icon fa-solid fa-user-clock"></i>
                <p>Aspirants</p>
              </a>
            </li>
            <?php endif; ?>

            <?php if ($superAdmin || $admin) : ?>
            <li class="nav-item">
              <a href="./user_accounts.php" class="nav-link <?= $currentPage === 'user_accounts.php' ? 'active' : '' ?>">
                <i class="nav-icon fa-solid fa-users"></i>
                <p>User Accounts</p>
              </a>
            </li>
            <?php endif; ?>

            <li class="nav-item">
              <a href="./messages.php" class="nav-link <?= $currentPage === 'messages.php' || $currentPage === 'conversation.php' ? 'active' : '' ?>">
                <i class="nav-icon fa-solid fa-message"></i>
                <p>Messages</p>
              </a>
            </li>

            <?php if ($superAdmin || $admin || $moderator) : ?>
            <li class="nav-item">
              <a href="./posts.php" class="nav-link <?= $currentPage === 'posts.php' || $currentPage === 'create_post.php' ? 'active' : '' ?>">
                <i class="nav-icon fa-solid fa-newspaper"></i>
                <p>Posts</p>
              </a>
            </li>
            <?php endif; ?>

            <li class="nav-item">
              <a href="./donations.php" class="nav-link <?= $currentPage === 'donations.php' ? 'active' : '' ?>">
                <i class="nav-icon fa-solid fa-hand-holding-heart"></i>
                <p>Donations</p>
              </a>
            </li>
            
            <!-- <li class="nav-item">
              <a href="./merchandise.php" class="nav-link">
                <i class="nav-icon fa-solid fa-tag"></i>
                <p>Merchandise</p>
              </a>
            </li> -->

            <!-- <li class="nav-item">
              <a href="../auth/login.php" class="nav-link">
                <i class="nav-icon fa-solid fa-arrow-right-from-bracket"></i>
                <p>Logout</p>
              </a>
            </li> -->
          </ul>
        </nav>
      </div>
    </aside>